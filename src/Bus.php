<?php

declare(strict_types=1);

namespace App;

use App\PPU\PPU;
use App\Rom\RomInterface;
use App\Type\UInt16;
use App\Type\UInt8;
use Exception;

final class Bus
{
    private array $memory = [];

    public function __construct(
        private readonly PPU $ppu,
        private readonly RomInterface $rom,
    ) {}

    public function getMemory(UInt16 $addr): UInt8
    {
        if ($addr->isInInterval(0, 0x1FFF)) {
            return new UInt8($this->memory[$addr->and(new UInt16(0b11111111111))->value]);
        } elseif ($addr->isIn(0x2000, 0x2001, 0x2003, 0x2005, 0x2006, 0x4014)) {
            throw new Exception('An attempt to read from register intended for writing (' . $addr->hexString() . ')');
        } elseif ($addr->isEqual(0x2002)) {
            return $this->ppu->getStatus();
        } elseif ($addr->isEqual(0x2004)) {
            return $this->ppu->getOamData();
        } elseif ($addr->isEqual(0x2007)) {
            return $this->ppu->getData();
        } elseif ($addr->isInInterval(0x2008, 0x3FFF)) {
            return $this->getMemory($addr->and(new UInt16(0x2007)));
        } elseif ($addr->isInInterval(0x4000, 0x4017)) {
            // TODO: NES APU and I/O registers
        } elseif ($addr->isInInterval(0x4018, 0x401F)) {
            // APU and I/O functionality that is normally disabled
        } elseif ($addr->isInInterval(0x8000, 0xFFFF)) {
            // TODO: offset
            $value = $this->rom->getPrgRom()[$addr->value - 0x8000];
            return new UInt8($value);
        }

        throw new Exception('An attempt to access an invalid memory address ' . $addr->hexString());
    }

    public function setMemory(UInt16 $addr, UInt8 $data): void
    {
        if ($addr->isInInterval(0, 0x1FFF)) {
            $this->memory[$addr->and(new UInt16(0b11111111111))->value] = $data->value;
        } elseif ($addr->isEqual(0x2000)) {
            $this->ppu->setControl($data);
        } elseif ($addr->isEqual(0x2001)) {
            $this->ppu->setMask($data);
        } elseif ($addr->isEqual(0x2002)) {
            throw new Exception('An attempt to write a value to PPUSTATUS');
        } elseif ($addr->isEqual(0x2003)) {
            $this->ppu->setOamAddr($data);
        } elseif ($addr->isEqual(0x2004)) {
            $this->ppu->setOamData($data);
        } elseif ($addr->isEqual(0x2005)) {
            $this->ppu->setScroll($data);
        } elseif ($addr->isEqual(0x2006)) {
            $this->ppu->setAddress($data);
        } elseif ($addr->isEqual(0x2007)) {
            $this->ppu->setData($data);
        } elseif ($addr->isInInterval(0x2008, 0x3FFF)) {
            $this->setMemory($addr->and(new UInt16(0x2007)), $data);
        } elseif ($addr->isEqual(0x4014)) {
            // TODO: writing to OAMDMA
            throw new Exception('TODO: writing to OAMDMA ' . $addr->hexString());
            //$this->ppu->setOamDma($data);
        } elseif ($addr->isInInterval(0x4000, 0x4017)) {
            // TODO: NES APU and I/O registers
            throw new Exception('TODO: NES APU and I/O registers ' . $addr->hexString());
        } elseif ($addr->isInInterval(0x4018, 0x401F)) {
            throw new Exception('APU and I/O functionality that is normally disabled ' . $addr->hexString());
        } elseif ($addr->isInInterval(0x8000, 0xFFFF)) {
            throw new Exception('An attempt to write to PRG ROM ' . $addr->hexString());
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . $addr->hexString());
        }
    }

    public function setMemoryUInt16(UInt16 $addr, UInt16 $data): void
    {
        if ($addr->isInInterval(0, 0x1FFF)) {
            $high = $data->value >> 8;
            $low = $data->value & 0xFF;

            $this->memory[$addr->value] = $low;
            $this->memory[$addr->value + 1] = $high;
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . $addr->hexString());
        }
    }

    public function getMemoryUInt16(UInt16 $addr): UInt16
    {
        if ($addr->isInInterval(0, 0x1FFF)) {
            $low = $this->memory[$addr->value];
            $high = $this->memory[$addr->increment()->value];
        } elseif ($addr->isInInterval(0x8000, 0xFFFF)) {
            // TODO: offset
            $low = $this->rom->getPrgRom()[$addr->value - 0x8000];
            $high = $this->rom->getPrgRom()[$addr->increment()->value - 0x8000];
        } else {
            throw new Exception('An attempt to access an invalid memory address ' . $addr->hexString());
        }

        $result = ($high << 8) | $low;

        return new UInt16($result);
    }
}
