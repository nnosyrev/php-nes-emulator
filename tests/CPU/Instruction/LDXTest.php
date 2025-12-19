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

final class LDXTest extends TestCase
{
    public function testLDX(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA2, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXZeroPage(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA6, 0x05, 0x00]);
        $CPU->writeMemory(new UInt16(0x05), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXZeroPageY(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xB6, 0x05, 0x00]);
        $CPU->setRegisterY(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXAbsolute(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xAE, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2210), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXAbsoluteY(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xBE, 0x10, 0x22, 0x00]);
        $CPU->setRegisterY(new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    private function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
