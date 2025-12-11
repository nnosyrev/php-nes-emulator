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

            if ($opcode === 0xA9) {
                // LDA
                $param = $program[$this->PC];

                $this->incrementPC();

                $this->setRegisterA($param);
                $this->setFlagZ($this->getRegisterA() === 0);
                $this->setFlagN(($this->getRegisterA() & 0b10000000) === 0b10000000);
            } elseif ($opcode === 0xA2) {
                // LDX
                $param = $program[$this->PC];

                $this->incrementPC();

                $this->setRegisterX($param);
                $this->setFlagZ($this->getRegisterX() === 0);
                $this->setFlagN(($this->getRegisterX() & 0b10000000) === 0b10000000);
            } elseif ($opcode === 0xAA) {
                // TAX
                $this->setRegisterX($this->getRegisterA());
                $this->setFlagZ($this->getRegisterX() === 0);
                $this->setFlagN(($this->getRegisterX() & 0b10000000) === 0b10000000);
            } elseif ($opcode === 0xE8) {
                // INX
                $this->setRegisterX($this->getRegisterX() + 1);
                $this->setFlagZ($this->getRegisterX() === 0);
                $this->setFlagN(($this->getRegisterX() & 0b10000000) === 0b10000000);
            } elseif ($opcode === 0x00) {
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
        $this->registerX = $registerX;
    }

    public function getRegisterX(): int
    {
        return $this->registerX;
    }

    private function setFlagZ(bool $flagZ): void
    {
        $this->flagZ = $flagZ;
    }

    public function getFlagZ(): bool
    {
        return $this->flagZ;
    }

    private function setFlagN(bool $flagN): void
    {
        $this->flagN = $flagN;
    }

    public function getFlagN(): bool
    {
        return $this->flagN;
    }
}
