<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class EORTest extends TestCase
{
    use CPUTestTrait;

    public function testEORImmediate(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x49, 0xA6, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x05 ^ 0xA6);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x45, 0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x08, 0xA1);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x05 ^ 0xA1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORZeroPageX(): void
    {
        $this->loadProgramToRom([0xA9, 0x23, 0xA2, 0x01, 0x55, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x06, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x23 ^ 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORAbsolute(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0x4D, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0210, 0x09);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 ^ 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORAbsoluteX(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0xA2, 0x03, 0x5D, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0213, 0x09);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 ^ 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORAbsoluteY(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0x59, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x03);
        $CPU->setMemory(0x0213, 0x09);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 ^ 0x09);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORIndirectX(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0xA2, 0x01, 0x41, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x01, 0x05);
        $CPU->setMemory(0x02, 0x07);
        $CPU->setMemory(0x0705, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 ^ 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testEORIndirectY(): void
    {
        $this->loadProgramToRom([0xA9, 0x75, 0x51, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x01);
        $CPU->setMemory(0x01, 0x03);
        $CPU->setMemory(0x02, 0x07);
        $CPU->setMemory(0x0704, 0x10);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x75 ^ 0x10);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
