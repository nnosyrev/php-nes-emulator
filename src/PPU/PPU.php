<?php

declare(strict_types=1);

namespace App\PPU;

use App\Mirroring;
use App\PPU\Register\AddressRegister;
use App\PPU\Register\ControlRegister;
use App\PPU\Register\ScrollRegister;
use App\Type\UInt16;
use App\Type\UInt8;
use Exception;
use SplFixedArray;

final class PPU
{
    // TODO: it's may be wrong (32)
    private SplFixedArray $palleteTable = new \SplFixedArray(32);

    private SplFixedArray $vram = new \SplFixedArray(2048);

    private UInt8 $dataBuf = new UInt8(0);

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
    private SplFixedArray $oamData = new \SplFixedArray(256);

    public function __construct(
        private readonly array $chrRom,
        private readonly Mirroring $mirroring,
        private readonly AddressRegister $addressRegister,
        private readonly ControlRegister $controlRegister,
        private readonly ScrollRegister $scrollRegister,
    ) {}

    public function setControl(UInt8 $value): void
    {
        $this->controlRegister->set($value);
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

    public function setOamAddr(UInt8 $value): void
    {
        $this->oamAddr = $value;
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

    public function getOamData(): UInt8
    {
        return new UInt8($this->oamData[$this->oamAddr->value]);
    }

    public function setScroll(UInt8 $value): void
    {
        $this->scrollRegister->set($value);
    }

    public function setAddress(UInt8 $value): void
    {
        $this->addressRegister->set($value);
    }

    public function readData(): UInt8
    {
        $addr = $this->addressRegister->get()->value;

        $this->addressRegister->add($this->controlRegister->getAddressIncrement());

        $result = $this->dataBuf;

        if (0x0000 <= $addr && $addr <= 0x1FFF) {
            $this->dataBuf = new UInt8($this->chrRom[$addr]);
        } elseif (0x2000 <= $addr && $addr <= 0x2FFF) {
            $this->dataBuf = new UInt8($this->vram[$this->mirrorVRamAddress(new UInt16($addr))->value]);
        } elseif (0x3000 <= $addr && $addr <= 0x3EFF) {
            throw new Exception('Addres space 0x3000..0x3eff is not expected to be used. Requested: 0x' . dechex($addr));
        } elseif (0x3F00 <= $addr && $addr <= 0x3FFF) {
            $this->dataBuf = new UInt8($this->palleteTable[$addr - 0x3f00]);
        } else {
            throw new Exception('Unexpected access to mirrored space 0x' . dechex($addr));
        }

        return $result;
    }

    private function mirrorVRamAddress(UInt16 $addr): UInt16
    {
        $mirroredAddr = $addr->and(new UInt16(0b10111111111111))->value;

        $vramIndex = $mirroredAddr - 0x2000;

        $nameTable = (int) floor($vramIndex / 0x400);

        if ($this->mirroring === Mirroring::Vertical && in_array($nameTable, [2, 3])) {
            $vramIndex = $vramIndex - 0x800;
        } elseif ($this->mirroring === Mirroring::Horizontal && in_array($nameTable, [1, 2])) {
            $vramIndex = $vramIndex - 0x400;
        } elseif ($this->mirroring === Mirroring::Horizontal && $nameTable === 3) {
            $vramIndex = $vramIndex - 0x800;
        }

        return new UInt16($vramIndex);
    }
}
