<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class LDYTest extends TestCase
{
    use CPUTestTrait;

    public function testLDYImmediate(): void
    {
        $this->loadProgramToRom([0xA0, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYZeroPage(): void
    {
        $this->loadProgramToRom([0xA4, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x05), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYZeroPageX(): void
    {
        $this->loadProgramToRom([0xB4, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->setMemory(new UInt16(0x06), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYAbsolute(): void
    {
        $this->loadProgramToRom([0xAC, 0x10, 0x22, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x2210), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testLDYAbsoluteX(): void
    {
        $this->loadProgramToRom([0xBC, 0x10, 0x22, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterX(new UInt8(0x03));
        $CPU->setMemory(new UInt16(0x2213), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }
}
