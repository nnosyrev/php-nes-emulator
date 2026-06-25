<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class LDATest extends TestCase
{
    use CPUTestTrait;

    public function testLDAImmediate(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAZeroPage(): void
    {
        $this->loadProgramToRom([0xA5, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x05, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAZeroPageX(): void
    {
        $this->loadProgramToRom([0xB5, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(0x01);
        $CPU->setMemory(0x06, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAIndirectX(): void
    {
        $this->loadProgramToRom([0xA1, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(0x01);
        $CPU->setMemory(0x01, 0x05);
        $CPU->setMemory(0x02, 0x07);
        $CPU->setMemory(0x0705, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAIndirectY(): void
    {
        $this->loadProgramToRom([0xB1, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x01);
        $CPU->setMemory(0x01, 0x03);
        $CPU->setMemory(0x02, 0x07);
        $CPU->setMemory(0x0704, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsolute(): void
    {
        $this->loadProgramToRom([0xAD, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0210, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsoluteX(): void
    {
        $this->loadProgramToRom([0xBD, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(0x03);
        $CPU->setMemory(0x0213, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsoluteY(): void
    {
        $this->loadProgramToRom([0xB9, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x03);
        $CPU->setMemory(0x0213, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAFlags(): void
    {
        $this->loadProgramToRom([0xA9, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x00);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
