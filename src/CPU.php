<?php

declare(strict_types=1);

namespace App;

final class CPU
{
    private Byte $registerA;
    private Byte $registerX;
    private Byte $registerY;

    private bool $flagC;
    private bool $flagZ;
    private bool $flagI;
    private bool $flagD;
    private bool $flagB;
    private bool $flagV;
    private bool $flagN;

    private int $SP;
    private int $PC;

    public function interpret(array $program): void
    {
        $this->PC = 0;

        while (true) {
            $opcode = $program[$this->PC];

            $this->incrementPC();

            if ($opcode === Opcodes::LDA) {
                // LDA
                $param = $program[$this->PC];
                $byte = new Byte($param);

                $this->incrementPC();

                $this->setRegisterA($byte);
                $this->setFlagZByValue($this->getRegisterA());
                $this->setFlagNByValue($this->getRegisterA());
            } elseif ($opcode === Opcodes::LDX) {
                // LDX
                $param = $program[$this->PC];
                $byte = new Byte($param);

                $this->incrementPC();

                $this->setRegisterX($byte);
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode === Opcodes::TAX) {
                // TAX
                $this->setRegisterX($this->getRegisterA());
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode === Opcodes::INX) {
                // INX
                $byte = $this->getRegisterX();
                $this->setRegisterX($byte->increment());
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode === Opcodes::BRK) {
                return;
            }
        }
    }

    private function incrementPC(): void
    {
        $this->PC += 1;
    }

    private function setRegisterA(Byte $byte): void
    {
        $this->registerA = $byte;
    }

    public function getRegisterA(): Byte
    {
        return $this->registerA;
    }

    private function setRegisterX(Byte $byte): void
    {
        $this->registerX = $byte;
    }

    public function getRegisterX(): Byte
    {
        return $this->registerX;
    }

    private function setFlagZ(bool $flagZ): void
    {
        $this->flagZ = $flagZ;
    }

    private function setFlagZByValue(Byte $byte): void
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

    private function setFlagNByValue(Byte $byte): void
    {
        $this->setFlagN(($byte->value & 0b10000000) === 0b10000000);
    }

    public function getFlagN(): bool
    {
        return $this->flagN;
    }
}
