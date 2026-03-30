<?php

declare(strict_types=1);

namespace App\PPU;

use App\Event\NMIEvent;
use App\Mirroring;
use App\PPU\Register\AddressRegister;
use App\PPU\Register\ControlRegister;
use App\PPU\Register\ScrollRegister;
use App\Rom\RomInterface;
use App\Type\UInt16;
use App\Type\UInt8;
use Exception;
use SplFixedArray;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class PPU
{
    private const CYCLES_PER_SCANLINE = 341;
    private const SCANLINES_PER_FRAME = 261;
    private const START_VBLANK_SCANLINE = 241;

    private SplFixedArray $palleteTable;

    private SplFixedArray $vram;

    private UInt8 $dataBuf;

    /*
     * PPUMASK - Rendering settings ($2001 write)
     *
     * 7  bit  0
     * ---- ----
     * BGRs bMmG
     * |||| ||||
     * |||| |||+- Greyscale (0: normal color, 1: greyscale)
     * |||| ||+-- 1: Show background in leftmost 8 pixels of screen, 0: Hide
     * |||| |+--- 1: Show sprites in leftmost 8 pixels of screen, 0: Hide
     * |||| +---- 1: Enable background rendering
     * |||+------ 1: Enable sprite rendering
     * ||+------- Emphasize red (green on PAL/Dendy)
     * |+-------- Emphasize green (red on PAL/Dendy)
     * +--------- Emphasize blue
     */
    private UInt8 $mask;

    /*
     * PPUSTATUS - Rendering events ($2002 read)
     *
     * 7  bit  0
     * ---- ----
     * VSOx xxxx
     * |||| ||||
     * |||+-++++- (PPU open bus or 2C05 PPU identifier)
     * ||+------- Sprite overflow flag
     * |+-------- Sprite 0 hit flag
     * +--------- Vblank flag, cleared on read. Unreliable; see below.
     */
    private UInt8 $status;

    /*
     * OAMADDR - Sprite RAM address ($2003 write)
     *
     * 7  bit  0
     * ---- ----
     * AAAA AAAA
     * |||| ||||
     * ++++-++++- OAM address
     */
    private UInt8 $oamAddr;

    /*
     * OAMDATA - Sprite RAM data ($2004 read/write)
     */
    private SplFixedArray $oamData;

    private int $cycles = 0;

    private int $scanlines = 0;

    public function __construct(
        private readonly RomInterface $rom,
        private readonly Renderer $renderer,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly AddressRegister $addressRegister,
        private readonly ControlRegister $controlRegister,
        private readonly ScrollRegister $scrollRegister,
    ) {
        // TODO: it's may be wrong (32)
        $this->palleteTable = new SplFixedArray(32);
        $this->vram = new SplFixedArray(2048);
        $this->dataBuf = new UInt8(0);
        $this->oamData = new SplFixedArray(256);
        $this->status = new UInt8(0);
    }

    public function setControl(UInt8 $value): void
    {
        $oldNMIEnableBit = $this->controlRegister->getNMIEnableBit();

        $this->controlRegister->set($value);

        // Changing NMI enable from 0 to 1 while the vblank flag in PPUSTATUS is 1 will immediately trigger an NMI
        if (!$oldNMIEnableBit && $this->controlRegister->getNMIEnableBit() && $this->getStatusVblankFlag()) {
            $this->dispatcher->dispatch(new NMIEvent());
        }
    }

    public function setMask(UInt8 $value): void
    {
        $this->mask = $value;
    }

    public function getStatus(): UInt8
    {
        $status = $this->status;

        // Vblank flag, cleared on read.
        $this->status = $this->status->and(new UInt8(0b01111111));

        $this->addressRegister->resetLatch();
        $this->scrollRegister->resetLatch();

        return $status;
    }

    private function setStatusSprite0Flag(): void
    {
        $this->status = $this->status->or(new UInt8(0b01000000));
    }

    private function setStatusVblankFlag(): void
    {
        $this->status = $this->status->or(new UInt8(0b10000000));
    }

    private function clearStatusVblankFlag(): void
    {
        $this->status = $this->status->and(new UInt8(0b01111111));
    }

    private function getStatusVblankFlag(): bool
    {
        return ($this->status->and(new UInt8(0b10000000))->value !== 0);
    }

    public function setOamAddr(UInt8 $value): void
    {
        $this->oamAddr = $value;
    }

    public function getOamData(): UInt8
    {
        return new UInt8($this->oamData[$this->oamAddr->value]);
    }

    public function setOamData(UInt8 $data): void
    {
        $this->oamData[$this->oamAddr->value] = $data->value;

        $this->oamAddr = $this->oamAddr->increment();
    }

    public function setOamDma(array $data): void
    {
        if (count($data) > 256) {
            throw new Exception('The number of array elements cannot exceed 256');
        }

        foreach ($data as $value) {
            $this->oamData[$this->oamAddr->value] = $value;
            $this->oamAddr = $this->oamAddr->increment();
        }
    }

    public function setScroll(UInt8 $value): void
    {
        $this->scrollRegister->set($value);
    }

    public function setAddress(UInt8 $value): void
    {
        $this->addressRegister->set($value);
    }

    public function getData(): UInt8
    {
        $addr = $this->addressRegister->get();

        $result = $this->dataBuf;

        $this->addressRegister->add($this->controlRegister->getAddressIncrement());

        if ($addr->isInInterval(0x0000, 0x1FFF)) {
            $this->dataBuf = new UInt8($this->rom->getChrRom()[$addr->value]);
        } elseif ($addr->isInInterval(0x2000, 0x2FFF)) {
            $this->dataBuf = new UInt8($this->vram[$this->mirrorVRamAddress($addr)->value]);
        } elseif ($addr->isInInterval(0x3000, 0x3EFF)) {
            throw new Exception('Address space 0x3000..0x3eff is not expected to be used. Requested: ' . $addr->hexString());
        } elseif ($addr->isInInterval(0x3F00, 0x3FFF)) {
            // These reads work differently than standard VRAM reads, as palette RAM is a separate memory
            // space internal to the PPU that is overlaid onto the PPU address space. The referenced 6-bit
            // palette data is returned immediately instead of going to the internal read buffer, and hence
            // no priming read is required. Simultaneously, the PPU also performs a normal read from PPU
            // memory at the specified address, "underneath" the palette data, and the result of this read
            // goes into the read buffer as normal. The old contents of the read buffer are discarded when
            // reading palettes, but by changing the address to point outside palette RAM and performing
            // one read, the contents of this shadowed memory (usually mirrored nametables) can be accessed
            $this->dataBuf = new UInt8($this->palleteTable[$addr->value - 0x3f00]);

            return $this->dataBuf;
        } else {
            throw new Exception('Unexpected access to mirrored space ' . $addr->hexString());
        }

        return $result;
    }

    public function setData(UInt8 $data): void
    {
        $addr = $this->addressRegister->get();

        if ($addr->isInInterval(0x0000, 0x1FFF)) {
            throw new Exception('Attempt to write to CHR ROM space ' . $addr->hexString());
        } elseif ($addr->isInInterval(0x2000, 0x2FFF)) {
            $this->vram[$this->mirrorVRamAddress($addr)->value] = $data->value;
        } elseif ($addr->isInInterval(0x3000, 0x3EFF)) {
            throw new Exception('Address space 0x3000..0x3eff is not expected to be used. Requested: ' . $addr->hexString());
        } elseif ($addr->isInInterval(0x3F00, 0x3FFF)) {
            $this->palleteTable[$addr->value - 0x3f00] = $data->value;
        } else {
            throw new Exception('Unexpected access to mirrored space ' . $addr->hexString());
        }

        $this->addressRegister->add($this->controlRegister->getAddressIncrement());
    }

    private function mirrorVRamAddress(UInt16 $addr): UInt16
    {
        $mirroredAddr = $addr->and(new UInt16(0b10111111111111))->value;

        $vramIndex = $mirroredAddr - 0x2000;

        $nameTable = (int) floor($vramIndex / 0x400);

        $mirroring = $this->rom->getMirroring();

        if ($mirroring === Mirroring::Vertical && in_array($nameTable, [2, 3])) {
            $vramIndex = $vramIndex - 0x800;
        } elseif ($mirroring === Mirroring::Horizontal && in_array($nameTable, [1, 2])) {
            $vramIndex = $vramIndex - 0x400;
        } elseif ($mirroring === Mirroring::Horizontal && $nameTable === 3) {
            $vramIndex = $vramIndex - 0x800;
        }

        return new UInt16($vramIndex);
    }

    public function run(int $cycles): void
    {
        $this->cycles += $cycles;

        if ($this->cycles >= self::CYCLES_PER_SCANLINE) {
            $this->scanlines++;
            $this->cycles = $this->cycles - self::CYCLES_PER_SCANLINE;

            if ($this->scanlines >= self::START_VBLANK_SCANLINE) {
                $this->setStatusVblankFlag();

                if ($this->controlRegister->getNMIEnableBit()) {
                    // Trigger NMI interrupt
                    $this->dispatcher->dispatch(new NMIEvent());
                }
            }

            if ($this->scanlines >= self::SCANLINES_PER_FRAME) {
                $this->clearStatusVblankFlag();
                $this->setStatusSprite0Flag();
                $this->scanlines = 0;

                $this->renderer->render($this);
            }
        }
    }

    public function getVRam(): SplFixedArray
    {
        return $this->vram;
    }

    public function getAllOamData(): SplFixedArray
    {
        return $this->oamData;
    }

    public function getSpriteChrBank(): int
    {
        return ($this->controlRegister->getSpritePatternTableBit() ? 1 : 0);
    }

    public function getBackgroundChrBank(): int
    {
        return ($this->controlRegister->getBackgroundPatternTableBit() ? 1 : 0);
    }
}
