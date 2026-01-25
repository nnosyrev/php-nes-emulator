<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class EORTest extends TestCase
{
    use CPUTestTrait;

    public function testEORImmediate(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x49, 0xA6, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05 ^ 0xA6);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x45, 0x08, 0x00]);
        $CPU->writeMemory(new UInt16(0x08), new UInt8(0xA1));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05 ^ 0xA1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x23, 0xA2, 0x01, 0x55, 0x05, 0x00]);
        $CPU->writeMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x23 ^ 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0x4D, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2210), new UInt8(0x09));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 ^ 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORAbsoluteX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0xA2, 0x03, 0x5D, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x09));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 ^ 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORAbsoluteY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0x59, 0x10, 0x22, 0x00]);
        $CPU->setRegisterY(new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x09));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 ^ 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORIndirectX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0xA2, 0x01, 0x41, 0x00, 0x00]);
        $CPU->writeMemory(new UInt16(0x01), new UInt8(0x05));
        $CPU->writeMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->writeMemory(new UInt16(0x0705), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 ^ 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORIndirectY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x75, 0x51, 0x01, 0x00]);
        $CPU->setRegisterY(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x01), new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->writeMemory(new UInt16(0x0704), new UInt8(0x10));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x75 ^ 0x10);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
