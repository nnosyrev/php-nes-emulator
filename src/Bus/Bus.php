<?php

declare(strict_types=1);

namespace App\Bus;

use App\Joystick;
use App\PPU\PPU;
use App\Rom\RomInterface;
use App\Util\UInt16;
use Exception;

final class Bus implements BusInterface
{
    private const PPUCTRL_REGISTER   = 0x2000;
    private const PPUMASK_REGISTER   = 0x2001;
    private const PPUSTATUS_REGISTER = 0x2002;
    private const OAMADDR_REGISTER   = 0x2003;
    private const OAMDATA_REGISTER   = 0x2004;
    private const PPUSCROLL_REGISTER = 0x2005;
    private const PPUADDR_REGISTER   = 0x2006;
    private const PPUDATA_REGISTER   = 0x2007;
    private const OAMDMA_REGISTER    = 0x4014;

    private const RAM_START = 0x0;
    private const RAM_END   = 0x1FFF;

    private const PRG_START = 0x8000;
    private const PRG_END   = 0xFFFF;

    private const PPU_REGISTERS_MIRRORS_START = 0x2008;
    private const PPU_REGISTERS_MIRRORS_END   = 0x3FFF;

    private const JOYSTICK_1_REGISTER = 0x4016;
    private const JOYSTICK_2_REGISTER = 0x4017;

    private const APU_REGISTERS_START = 0x4000;
    private const APU_REGISTERS_END   = 0x4015;

    private const RAM_MIRRORING = 0b11111111111;
    private const PPU_MIRRORING = self::PPUDATA_REGISTER;

    private array $memory = [];

    public function __construct(
        private readonly PPU $ppu,
        private readonly RomInterface $rom,
        private readonly Joystick $joystick,
    ) {}

    public function getMemory(int /* UInt16 */ $addr): int /* UInt8 */
    {
        return match (true) {
            UInt16::inInterval($addr, self::PRG_START, self::PRG_END) => $this->rom->getPrgRom()[$addr - self::PRG_START],
            UInt16::inInterval($addr, self::RAM_START, self::RAM_END) => $this->memory[$this->ramMirror($addr)],
            $addr === self::JOYSTICK_1_REGISTER => $this->joystick->get(),
            $addr === self::JOYSTICK_2_REGISTER => 0,
            $addr === self::PPUDATA_REGISTER => $this->ppu->getData(),
            $addr === self::PPUSTATUS_REGISTER => $this->ppu->getStatus(),
            $addr === self::OAMDATA_REGISTER => $this->ppu->getOamData(),
            UInt16::inInterval($addr, self::PPU_REGISTERS_MIRRORS_START, self::PPU_REGISTERS_MIRRORS_END) => $this->getMemory($this->ppuMirror($addr)),
            UInt16::inInterval($addr, self::APU_REGISTERS_START, self::APU_REGISTERS_END) => 0,
            default => throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr)),
        };
    }

    public function setMemory(int /* UInt16 */ $addr, int /* UInt8 */ $data): void
    {
        match (true) {
            UInt16::inInterval($addr, self::RAM_START, self::RAM_END) => $this->memory[$this->ramMirror($addr)] = $data,
            $addr === self::JOYSTICK_1_REGISTER => $this->joystick->set($data),
            $addr === self::JOYSTICK_2_REGISTER => 0,
            $addr === self::OAMDMA_REGISTER => $this->setOamDma($data),
            // Excluding OAMDMA_REGISTER (0x4014) which is higher
            UInt16::inInterval($addr, self::APU_REGISTERS_START, self::APU_REGISTERS_END) => 0,
            $addr === self::PPUADDR_REGISTER => $this->ppu->setAddress($data),
            $addr === self::PPUCTRL_REGISTER => $this->ppu->setControl($data),
            $addr === self::PPUMASK_REGISTER => $this->ppu->setMask($data),
            $addr === self::PPUSTATUS_REGISTER => throw new Exception('An attempt to write a value to PPUSTATUS'),
            $addr === self::OAMADDR_REGISTER => $this->ppu->setOamAddr($data),
            $addr === self::OAMDATA_REGISTER => $this->ppu->setOamData($data),
            $addr === self::PPUSCROLL_REGISTER => $this->ppu->setScroll($data),
            $addr === self::PPUDATA_REGISTER => $this->ppu->setData($data),
            UInt16::inInterval($addr, self::PPU_REGISTERS_MIRRORS_START, self::PPU_REGISTERS_MIRRORS_END) => $this->setMemory($this->ppuMirror($addr), $data),
            UInt16::inInterval($addr, self::PRG_START, self::PRG_END) => throw new Exception('An attempt to write to PRG ROM ' . UInt16::hexString($addr)),
            default => throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr))
        };
    }

    public function setMemoryUInt16(int /* UInt16 */ $addr, int /* UInt16 */ $data): void
    {
        if (!UInt16::inInterval($addr, self::RAM_START, self::RAM_END)) {
            throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr));
        }

        $high = $data >> 8;
        $low = $data & 0xFF;

        $this->memory[$addr] = $low;
        $this->memory[$addr + 1] = $high;
    }

    public function getMemoryUInt16(int /* UInt16 */ $addr): int /* UInt16 */
    {
        if (UInt16::inInterval($addr, self::RAM_START, self::RAM_END)) {
            $low = $this->memory[$addr];
            $high = $this->memory[$addr + 1];
        } elseif (UInt16::inInterval($addr, self::PRG_START, self::PRG_END)) {
            $low = $this->rom->getPrgRom()[$addr - self::PRG_START];
            $high = $this->rom->getPrgRom()[$addr + 1 - self::PRG_START];
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr));
        }

        // SycleStorage::push(29985, 25, "read");
        // SycleStorage::push(29986, 95, "read");

        return ($high << 8) | $low;
    }

    private function setOamDma(int /* UInt8 */ $data): void
    {
        $readFrom = $data << 8;
        $readTo = $readFrom | 0b11111111;

        for ($addr = $readFrom; $addr <= $readTo; $addr++) {
            $this->ppu->setOamData($this->getMemory($addr));
        }
    }

    private function ramMirror(int /* UInt16 */ $addr): int /* UInt16 */
    {
        return $addr & self::RAM_MIRRORING;
    }

    private function ppuMirror(int /* UInt16 */ $addr): int /* UInt16 */
    {
        return $addr & self::PPU_MIRRORING;
    }
}
