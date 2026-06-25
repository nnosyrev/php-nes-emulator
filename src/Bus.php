<?php

declare(strict_types=1);

namespace App;

use App\PPU\PPU;
use App\PPU\Renderer;
use App\Rom\RomInterface;
use App\UI\UIInterface;
use App\Util\Debug;
use App\Util\UInt16;
use Exception;

final class Bus
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

    private array $memory = [];

    public function __construct(
        private readonly PPU $ppu,
        private readonly RomInterface $rom,
        private readonly UIInterface $ui,
        private readonly Renderer $renderer,
        private readonly Joystick $joystick,
    ) {}

    public function getMemory(int /* UInt16 */ $addr): int /* UInt8 */
    {
        if (UInt16::isInInterval($addr, 0x8000, 0xFFFF)) {
            return $this->rom->getPrgRom()[$addr - 0x8000];
        } elseif (UInt16::isInInterval($addr, 0, 0x1FFF)) {
            return $this->memory[$this->mirror($addr)];
        } elseif ($addr === 0x4016) {
            return $this->joystick->get();
        } elseif ($addr === self::PPUDATA_REGISTER) {
            return $this->ppu->getData();
        } elseif ($addr === self::PPUSTATUS_REGISTER) {
            return $this->ppu->getStatus();
        } elseif ($addr === self::OAMDATA_REGISTER) {
            return $this->ppu->getOamData();
        } elseif (UInt16::isInInterval($addr, 0x2008, 0x3FFF)) {
            return $this->getMemory($addr & 0x2007);
        } elseif (UInt16::isInInterval($addr, 0x4000, 0x4017)) {
            // TODO: NES APU and I/O registers
            return 0;
        } elseif (UInt16::isInInterval($addr, 0x4018, 0x401F)) {
            // APU and I/O functionality that is normally disabled
        } elseif (UInt16::isIn($addr,
            self::PPUCTRL_REGISTER, self::PPUMASK_REGISTER, self::OAMADDR_REGISTER,
            self::PPUSCROLL_REGISTER, self::PPUADDR_REGISTER, self::OAMDMA_REGISTER
        )) {
            throw new Exception('An attempt to read from register intended for writing (' . UInt16::hexString($addr) . ')');
        }

        throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr));
    }

    public function setMemory(int /* UInt16 */ $addr, int /* UInt8 */ $data): void
    {
        if (UInt16::isInInterval($addr, 0, 0x1FFF)) {
            $this->memory[$this->mirror($addr)] = $data;
        } elseif ($addr === 0x4016) {
            $this->joystick->set($data);
        } elseif ($addr === self::OAMDMA_REGISTER) {
            $this->setOamDma($data);
        } elseif (UInt16::isInInterval($addr, 0x4000, 0x4017)) {
            // TODO: NES APU and I/O registers
            //throw new Exception('TODO: NES APU and I/O registers ' . $addr->hexString());
        } elseif ($addr === self::PPUADDR_REGISTER) {
            $this->ppu->setAddress($data);
        } elseif ($addr === self::PPUCTRL_REGISTER) {
            $this->ppu->setControl($data);
        } elseif ($addr === self::PPUMASK_REGISTER) {
            $this->ppu->setMask($data);
        } elseif ($addr === self::PPUSTATUS_REGISTER) {
            throw new Exception('An attempt to write a value to PPUSTATUS');
        } elseif ($addr === self::OAMADDR_REGISTER) {
            $this->ppu->setOamAddr($data);
        } elseif ($addr === self::OAMDATA_REGISTER) {
            $this->ppu->setOamData($data);
        } elseif ($addr === self::PPUSCROLL_REGISTER) {
            $this->ppu->setScroll($data);
        } elseif ($addr === self::PPUDATA_REGISTER) {
            $this->ppu->setData($data);
        } elseif (UInt16::isInInterval($addr, 0x2008, 0x3FFF)) {
            $this->setMemory($addr & 0x2007, $data);
        } elseif (UInt16::isInInterval($addr, 0x4018, 0x401F)) {
            throw new Exception('APU and I/O functionality that is normally disabled ' . UInt16::hexString($addr));
        } elseif (UInt16::isInInterval($addr, 0x8000, 0xFFFF)) {
            throw new Exception('An attempt to write to PRG ROM ' . UInt16::hexString($addr));
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr));
        }
    }

    public function setMemoryUInt16(int /* UInt16 */ $addr, int /* UInt16 */ $data): void
    {
        if (UInt16::isInInterval($addr, 0, 0x1FFF)) {
            $high = $data >> 8;
            $low = $data & 0xFF;

            $this->memory[$addr] = $low;
            $this->memory[$addr + 1] = $high;
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr));
        }
    }

    public function getMemoryUInt16(int /* UInt16 */ $addr): int /* UInt16 */
    {
        if (UInt16::isInInterval($addr, 0, 0x1FFF)) {
            $low = $this->memory[$addr];
            $high = $this->memory[$addr + 1];
        } elseif (UInt16::isInInterval($addr, 0x8000, 0xFFFF)) {
            // TODO: offset
            $low = $this->rom->getPrgRom()[$addr - 0x8000];
            $high = $this->rom->getPrgRom()[$addr + 1 - 0x8000];
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . UInt16::hexString($addr));
        }

        $result = ($high << 8) | $low;

        return $result;
    }

    private function setOamDma(int /* UInt8 */ $data): void
    {
        $readFrom = $data << 8;
        $readTo = $readFrom | 0b11111111;

        for ($addr = $readFrom; $addr <= $readTo; $addr++) {
            $this->ppu->setOamData($this->getMemory($addr));
        }
    }

    private function mirror(int /* UInt16 */ $addr): int /* UInt16 */
    {
        return $addr & 0b11111111111;
    }
}
