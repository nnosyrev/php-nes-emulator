<?php

declare(strict_types=1);

namespace App\Tests\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;

final class LDATest extends TestCase
{
    public function testLDAImmediate(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA9, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAZeroPage(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA5, 0x05, 0x00]);
        $CPU->writeMemory(new UInt16(0x05), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAZeroPageX(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xB5, 0x05, 0x00]);
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAIndirectX(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA1, 0x00, 0x00]);
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x01), new UInt8(0x05));
        $CPU->writeMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->writeMemory(new UInt16(0x0705), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAIndirectY(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xB1, 0x01, 0x00]);
        $CPU->setRegisterY(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x01), new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->writeMemory(new UInt16(0x0704), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsolute(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xAD, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2210), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsoluteX(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xBD, 0x10, 0x22, 0x00]);
        $CPU->setRegisterX(new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsoluteY(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xB9, 0x10, 0x22, 0x00]);
        $CPU->setRegisterY(new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAFlags(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA9, 0x00, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x00);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    private function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
