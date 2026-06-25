<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class LDXTest extends TestCase
{
    use CPUTestTrait;

    public function testLDX(): void
    {
        $this->loadProgramToRom([0xA2, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX(), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXZeroPage(): void
    {
        $this->loadProgramToRom([0xA6, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x05, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXZeroPageY(): void
    {
        $this->loadProgramToRom([0xB6, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x01);
        $CPU->setMemory(0x06, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXAbsolute(): void
    {
        $this->loadProgramToRom([0xAE, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0210, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testLDXAbsoluteY(): void
    {
        $this->loadProgramToRom([0xBE, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x03);
        $CPU->setMemory(0x0213, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
