<?php

declare(strict_types=1);

namespace App;

final class CPU
{
    private int $registerA = 0;
    private int $registerX;
    private int $registerY;

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

                $this->incrementPC();

                $this->setRegisterA($param);
                $this->setFlagZByValue($this->getRegisterA());
                $this->setFlagNByValue($this->getRegisterA());
            } elseif ($opcode === Opcodes::LDX) {
                // LDX
                $param = $program[$this->PC];

                $this->incrementPC();

                $this->setRegisterX($param);
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode === Opcodes::TAX) {
                // TAX
                $this->setRegisterX($this->getRegisterA());
                $this->setFlagZByValue($this->getRegisterX());
                $this->setFlagNByValue($this->getRegisterX());
            } elseif ($opcode === Opcodes::INX) {
                // INX
                $this->setRegisterX($this->getRegisterX() + 1);
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

    private function setRegisterA(int $registerA): void
    {
        $this->registerA = $registerA;
    }

    public function getRegisterA(): int
    {
        return $this->registerA;
    }

    private function setRegisterX(int $registerX): void
    {
        $this->registerX = $registerX % 256;
    }

    public function getRegisterX(): int
    {
        return $this->registerX;
    }

    private function setFlagZ(bool $flagZ): void
    {
        $this->flagZ = $flagZ;
    }

    private function setFlagZByValue(int $value): void
    {
        $this->setFlagZ($value === 0);
    }

    public function getFlagZ(): bool
    {
        return $this->flagZ;
    }

    private function setFlagN(bool $flagN): void
    {
        $this->flagN = $flagN;
    }

    private function setFlagNByValue(int $value): void
    {
        $this->setFlagN(($value & 0b10000000) === 0b10000000);
    }

    public function getFlagN(): bool
    {
        return $this->flagN;
    }
}
