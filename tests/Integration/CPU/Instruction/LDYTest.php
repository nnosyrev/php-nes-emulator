<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class LDYTest extends TestCase
{
    use CPUTestTrait;

    public function testLDYImmediate(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA0, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA4, 0x05, 0x00]);
        $CPU->writeMemory(new UInt16(0x05), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xB4, 0x05, 0x00]);
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->writeMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xAC, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2210), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYAbsoluteX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xBC, 0x10, 0x22, 0x00]);
        $CPU->setRegisterX(new UInt8(0x03));
        $CPU->writeMemory(new UInt16(0x2213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }
}
