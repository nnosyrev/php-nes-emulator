<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
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

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAZeroPage(): void
    {
        $this->loadProgramToRom([0xA5, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x05), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAZeroPageX(): void
    {
        $this->loadProgramToRom([0xB5, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->setMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAIndirectX(): void
    {
        $this->loadProgramToRom([0xA1, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->setMemory(new UInt16(0x01), new UInt8(0x05));
        $CPU->setMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->setMemory(new UInt16(0x0705), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAIndirectY(): void
    {
        $this->loadProgramToRom([0xB1, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(new UInt8(0x01));
        $CPU->setMemory(new UInt16(0x01), new UInt8(0x03));
        $CPU->setMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->setMemory(new UInt16(0x0704), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsolute(): void
    {
        $this->loadProgramToRom([0xAD, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x0210), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsoluteX(): void
    {
        $this->loadProgramToRom([0xBD, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(new UInt8(0x03));
        $CPU->setMemory(new UInt16(0x0213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAAbsoluteY(): void
    {
        $this->loadProgramToRom([0xB9, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(new UInt8(0x03));
        $CPU->setMemory(new UInt16(0x0213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAFlags(): void
    {
        $this->loadProgramToRom([0xA9, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x00);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
