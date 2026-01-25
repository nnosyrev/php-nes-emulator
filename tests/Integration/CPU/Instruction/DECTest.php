<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class DECTest extends TestCase
{
    use CPUTestTrait;

    public function testDECZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x85, 0x01, 0xC6, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x01))->value, 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x01))));
    }

    public function testDECZeroPageZero(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x01, 0x85, 0x02, 0xC6, 0x02, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x02))->value, 0x01 - 1);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x02))));
    }

    public function testDECZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA2, 0x01, 0x85, 0x02, 0xD6, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x02))->value, 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x02))));
    }

    public function testDECAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->writeMemory(new UInt16(0x0201), new UInt8(0x04));
        $CPU->load([0xCE, 0x01, 0x02, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0201))->value, 0x03);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x0201))));
    }

    public function testDECAbsoluteX(): void
    {
        $CPU = $this->CPU;
        $CPU->writeMemory(new UInt16(0x0202), new UInt8(0x04));
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->load([0xDE, 0x01, 0x02, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0202))->value, 0x03);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x0202))));
    }
}
