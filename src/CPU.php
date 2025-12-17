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
                $this->setFlagZByValue($this->getRegisterA());
                $this->setFlagNByValue($this->getRegisterA());
            } elseif ($opcode->value === 0xA5) {
                // LDA zero page
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->toUInt16());

                $this->incrementPC();

                $this->setRegisterA($value);
                $this->setFlagZByValue($this->getRegisterA());
                $this->setFlagNByValue($this->getRegisterA());
            } elseif ($opcode->value === 0xB5) {
                // LDA zero page, X
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->add($this->getRegisterX())->toUInt16());

                $this->incrementPC();

                $this->setRegisterA($value);
                $this->setFlagZByValue($this->getRegisterA());
                $this->setFlagNByValue($this->getRegisterA());
            } elseif ($opcode->value === 0xA1) {
                // LDA Indirect X
                $param = $this->readMemory($this->PC);
                $value = $this->readMemory($param->add($this->getRegisterX())->toUInt16());

                $low = $this->readMemory($value->toUInt16());
                $high = $this->readMemory($value->increment()->toUInt16());

                $result = ($high->value << 8) | $low->value;

                $resValue = $this->readMemory(new UInt16($result));

                $this->incrementPC();

                $this->setRegisterA($resValue);
                $this->setFlagZByValue($this->getRegisterA());
                $this->setFlagNByValue($this->getRegisterA());
            } elseif ($opcode->value === Opcodes::LDX) {
                // LDX
                $param = $this->readMemory($this->PC);

                $this->incrementPC();

                $this->setRegisterX($param);
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode->value === Opcodes::TAX) {
                // TAX
                $this->setRegisterX($this->getRegisterA());
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode->value === Opcodes::INX) {
                // INX
                $byte = $this->getRegisterX();
                $this->setRegisterX($byte->increment());
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
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
    }

    public function getRegisterA(): UInt8
    {
        return $this->registerA;
    }

    private function setRegisterX(UInt8 $byte): void
    {
        $this->registerX = $byte;
    }

    public function getRegisterX(): UInt8
    {
        return $this->registerX;
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

    private function writeMemory(UInt16 $addr, UInt8 $data): void
    {
        $this->memory[$addr->value] = $data->value;
    }

    private function readMemory(UInt16 $addr): UInt8
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
        $high = $this->memory[$addr->value + 1];

        $res = ($high << 8) | $low;

        return new UInt16($res);
    }
}
