<?php

declare(strict_types=1);

namespace App\CPU;

use App\CPU\Exception\BreakException;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\UInt16;
use App\UInt8;

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

    public function __construct(
        private readonly OpcodeCollection $opcodeCollection,
        private readonly InstructionFactory $instructionFactory,
        private readonly ModeFactory $modeFactory,
    ) {}

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
            $code = $this->readMemory($this->PC);

            $this->incrementPC();

            $opcode = $this->opcodeCollection->get($code->value);

            $instruction = $this->instructionFactory->make($opcode->instructionClass);

            $mode = $this->modeFactory->make($opcode->modeClass);

            try {
                $instruction->execute($this, $mode);
            } catch (BreakException $e) {
                return;
            }

            $this->addToPC(new UInt8($opcode->length - 1));
        }
    }

    public function getPC(): UInt16
    {
        return $this->PC;
    }

    public function incrementPC(): void
    {
        $this->PC = $this->PC->increment();
    }

    public function addToPC(UInt8 $add): void
    {
        $this->PC = $this->PC->add($add);
    }

    public function setRegisterA(UInt8 $byte): void
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
