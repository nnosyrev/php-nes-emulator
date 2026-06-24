<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ANDTest extends TestCase
{
    use CPUTestTrait;

    public function testANDImmediate(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x29, 0xA6, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x05 & 0xA6);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x25, 0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x08, 0xA1);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x05 & 0xA1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDZeroPageX(): void
    {
        $this->loadProgramToRom([0xA9, 0x23, 0xA2, 0x01, 0x35, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x06, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x23 & 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDIndirectX(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0xA2, 0x01, 0x21, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x01, 0x05);
        $CPU->setMemory(0x02, 0x07);
        $CPU->setMemory(0x0705, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 & 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDIndirectY(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0x31, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x01);
        $CPU->setMemory(0x01, 0x03);
        $CPU->setMemory(0x02, 0x07);
        $CPU->setMemory(0x0704, 0x10);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 & 0x10);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDAbsolute(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0x2D, 0x20, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0120, 0x09);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 & 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDAbsoluteX(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0xA2, 0x03, 0x3D, 0x20, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0123, 0x09);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 & 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testANDAbsoluteY(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0x39, 0x20, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x03);
        $CPU->setMemory(0x0123, 0x09);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 & 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
