<?php

declare(strict_types=1);

namespace App;

final class CPU
{
    private const PRG_ROM_START = 0x8000;

    private UInt8 $registerA;
    private UInt8 $registerX;
    private UInt8 $registerY;

    private bool $flagC;
    private bool $flagZ;
    private bool $flagI;
    private bool $flagD;
    private bool $flagB;
    private bool $flagV;
    private bool $flagN;

    private int $SP;
    private UInt16 $PC;

    private array $memory = [];

    public function load(array $program): void
    {
        $this->PC = new UInt16(self::PRG_ROM_START);

        $current = self::PRG_ROM_START;
        foreach ($program as &$byte) {
            UInt8::validate($byte);

            $this->memory[$current] = $byte;
            $current++;
        }
    }

    public function run(): void
    {
        while (true) {
            $opcode = $this->readMemory($this->PC);

            $this->incrementPC();

            if ($opcode->value === Opcodes::LDA) {
                // LDA Immediate
                $param = $this->readMemory($this->PC);

                $this->incrementPC();

                $this->setRegisterA($param);
            } elseif ($opcode->value === 0xA5) {
                // LDA zero page
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->toUInt16());

                $this->incrementPC();

                $this->setRegisterA($value);
            } elseif ($opcode->value === 0xB5) {
                // LDA zero page, X
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->add($this->getRegisterX())->toUInt16());

                $this->incrementPC();

                $this->setRegisterA($value);
            } elseif ($opcode->value === 0xA1) {
                // LDA Indirect X
                $param = $this->readMemory($this->PC);

                $ptr = $param->add($this->getRegisterX())->toUInt16();

                $low = $this->readMemory($ptr);
                $high = $this->readMemory($ptr->increment());

                $result = ($high->value << 8) | $low->value;

                $resValue = $this->readMemory(new UInt16($result));

                $this->incrementPC();

                $this->setRegisterA($resValue);
            } elseif ($opcode->value === 0xB1) {
                // LDA Indirect Y
                $param = $this->readMemory($this->PC);

                $ptr = $param->toUInt16();

                $low = $this->readMemory($ptr);
                $high = $this->readMemory($ptr->increment());

                $result = ($high->value << 8) | $low->value;

                $addr = (new UInt16($result))->add($this->getRegisterY());

                $resValue = $this->readMemory($addr);

                $this->incrementPC();

                $this->setRegisterA($resValue);
            } elseif ($opcode->value === 0xAD) {
                // LDA Absolute
                $param = $this->readMemoryUInt16($this->PC);

                $resValue = $this->readMemory($param);

                $this->incrementPC();
                $this->incrementPC();

                $this->setRegisterA($resValue);
            } elseif ($opcode->value === 0xBD) {
                // LDA Absolute X
                $param = $this->readMemoryUInt16($this->PC);

                $resValue = $this->readMemory($param->add($this->getRegisterX()));

                $this->incrementPC();
                $this->incrementPC();

                $this->setRegisterA($resValue);
            } elseif ($opcode->value === 0xB9) {
                // LDA Absolute Y
                $param = $this->readMemoryUInt16($this->PC);

                $resValue = $this->readMemory($param->add($this->getRegisterY()));

                $this->incrementPC();
                $this->incrementPC();

                $this->setRegisterA($resValue);
            } elseif ($opcode->value === Opcodes::LDX) {
                // LDX Immediate
                $param = $this->readMemory($this->PC);

                $this->incrementPC();

                $this->setRegisterX($param);
            } elseif ($opcode->value === 0xA6) {
                // LDX Zero page
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->toUInt16());

                $this->incrementPC();

                $this->setRegisterX($value);
            } elseif ($opcode->value === 0xB6) {
                // LDX Zero page Y
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->add($this->getRegisterY())->toUInt16());

                $this->incrementPC();

                $this->setRegisterX($value);
            } elseif ($opcode->value === 0xAE) {
                // LDX Absolute
                $param = $this->readMemoryUInt16($this->PC);

                $resValue = $this->readMemory($param);

                $this->incrementPC();
                $this->incrementPC();

                $this->setRegisterX($resValue);
            } elseif ($opcode->value === 0xBE) {
                // LDX Absolute Y
                $param = $this->readMemoryUInt16($this->PC);

                $resValue = $this->readMemory($param->add($this->getRegisterY()));

                $this->incrementPC();
                $this->incrementPC();

                $this->setRegisterX($resValue);
            } elseif ($opcode->value === Opcodes::TAX) {
                // TAX
                $this->setRegisterX($this->getRegisterA());
            } elseif ($opcode->value === Opcodes::INX) {
                // INX
                $byte = $this->getRegisterX();
                $this->setRegisterX($byte->increment());
            } elseif ($opcode->value === Opcodes::BRK) {
                return;
            }
        }
    }

    private function incrementPC(): void
    {
        $this->PC = $this->PC->increment();
    }

    private function setRegisterA(UInt8 $byte): void
    {
        $this->registerA = $byte;

        $this->setFlagZByValue($this->getRegisterA());
        $this->setFlagNByValue($this->getRegisterA());
    }

    public function getRegisterA(): UInt8
    {
        return $this->registerA;
    }

    public function setRegisterX(UInt8 $byte): void
    {
        $this->registerX = $byte;

        $this->setFlagZByValue($this->getRegisterX());
        $this->setFlagNByValue($this->getRegisterX());
    }

    public function getRegisterX(): UInt8
    {
        return $this->registerX;
    }

    public function setRegisterY(UInt8 $byte): void
    {
        $this->registerY = $byte;
    }

    public function getRegisterY(): UInt8
    {
        return $this->registerY;
    }

    private function setFlagZ(bool $flagZ): void
    {
        $this->flagZ = $flagZ;
    }

    private function setFlagZByValue(UInt8 $byte): void
    {
        $this->setFlagZ($byte->value === 0);
    }

    public function getFlagZ(): bool
    {
        return $this->flagZ;
    }

    private function setFlagN(bool $flagN): void
    {
        $this->flagN = $flagN;
    }

    private function setFlagNByValue(UInt8 $byte): void
    {
        $this->setFlagN(($byte->value & 0b10000000) === 0b10000000);
    }

    public function getFlagN(): bool
    {
        return $this->flagN;
    }

    public function writeMemory(UInt16 $addr, UInt8 $data): void
    {
        $this->memory[$addr->value] = $data->value;
    }

    public function readMemory(UInt16 $addr): UInt8
    {
        return new UInt8($this->memory[$addr->value]);
    }

    public function writeMemoryUInt16(UInt16 $addr, UInt16 $data): void
    {
        $high = $data->value >> 8;
        $low = $data->value & 0xFF;

        $this->memory[$addr->value] = $low;
        $this->memory[$addr->value + 1] = $high;
    }

    public function readMemoryUInt16(UInt16 $addr): UInt16
    {
        $low = $this->memory[$addr->value];
        $high = $this->memory[$addr->increment()->value];

        $res = ($high << 8) | $low;

        return new UInt16($res);
    }
}
