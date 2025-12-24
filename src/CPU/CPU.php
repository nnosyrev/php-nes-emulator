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
    private const STACK_START = 0x0100;
    private const SP_END = 0xFF;

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

    private UInt8 $SP;
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
        $this->SP = new UInt8(self::SP_END);

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

    public function setSP(UInt8 $data): void
    {
        $this->SP = $data;
    }

    public function getSP(): UInt8
    {
        return $this->SP;
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

        $this->setFlagsZNByValue($this->getRegisterA());
    }

    public function getRegisterA(): UInt8
    {
        return $this->registerA;
    }

    public function setRegisterX(UInt8 $byte): void
    {
        $this->registerX = $byte;

        $this->setFlagsZNByValue($this->getRegisterX());
    }

    public function getRegisterX(): UInt8
    {
        return $this->registerX;
    }

    public function setRegisterY(UInt8 $byte): void
    {
        $this->registerY = $byte;

        $this->setFlagsZNByValue($this->getRegisterY());
    }

    public function getRegisterY(): UInt8
    {
        return $this->registerY;
    }

    public function getFlagC(): bool
    {
        return $this->flagC;
    }

    public function setFlagC(bool $flagC): void
    {
        $this->flagC = $flagC;
    }

    public function getFlagD(): bool
    {
        return $this->flagD;
    }

    public function setFlagD(bool $flagD): void
    {
        $this->flagD = $flagD;
    }

    public function getFlagI(): bool
    {
        return $this->flagI;
    }

    public function setFlagI(bool $flagI): void
    {
        $this->flagI = $flagI;
    }

    public function getFlagV(): bool
    {
        return $this->flagV;
    }

    public function setFlagV(bool $flagV): void
    {
        $this->flagV = $flagV;
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

    public function setFlagsZNByValue(UInt8 $value): void
    {
        $this->setFlagZByValue($value);
        $this->setFlagNByValue($value);
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

    public function pushToStack(UInt8 $data): void
    {
        $this->writeMemory((new UInt16(self::STACK_START))->add($this->SP), $data);

        $this->SP = $this->SP->decrement();
    }

    public function popFromStack(): UInt8
    {
        $this->SP = $this->SP->increment();

        return $this->readMemory((new UInt16(self::STACK_START))->add($this->SP));
    }
}
