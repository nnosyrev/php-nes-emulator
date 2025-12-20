<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use Tests\Integration\CPU\AbstractCPUTest;

final class ORATest extends AbstractCPUTest
{
    public function testORAImmediate(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x09, 0xA6, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05 | 0xA6);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x05, 0x08, 0x00]);
        $CPU->writeMemory(new UInt16(0x08), new UInt8(0xA1));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05 | 0xA1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x23, 0xA2, 0x01, 0x15, 0x05, 0x00]);
        $CPU->writeMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x23 | 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAIndirectX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0xA2, 0x01, 0x01, 0x00, 0x00]);
        $CPU->writeMemory(new UInt16(0x01), new UInt8(0x05));
        $CPU->writeMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->writeMemory(new UInt16(0x0705), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 | 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAIndirectY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0x11, 0x01, 0x00]);
        $CPU->setRegisterY(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x01), new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->writeMemory(new UInt16(0x0704), new UInt8(0x10));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 | 0x10);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0x0D, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2210), new UInt8(0x09));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 | 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAAbsoluteX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0xA2, 0x03, 0x1D, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x09));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 | 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testORAAbsoluteY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0x19, 0x10, 0x22, 0x00]);
        $CPU->setRegisterY(new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x09));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 | 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
