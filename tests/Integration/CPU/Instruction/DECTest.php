<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class DECTest extends TestCase
{
    use CPUTestTrait;

    public function testDECZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x85, 0x01, 0xC6, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x01), 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x01)));
    }

    public function testDECZeroPageZero(): void
    {
        $this->loadProgramToRom([0xA9, 0x01, 0x85, 0x02, 0xC6, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x02), 0x01 - 1);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x02)));
    }

    public function testDECZeroPageX(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x01, 0x85, 0x02, 0xD6, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x02), 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x02)));
    }

    public function testDECAbsolute(): void
    {
        $this->loadProgramToRom([0xCE, 0x01, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0201, 0x04);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0201), 0x03);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x0201)));
    }

    public function testDECAbsoluteX(): void
    {
        $this->loadProgramToRom([0xDE, 0x01, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0202, 0x04);
        $CPU->setRegisterX(0x01);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0202), 0x03);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x0202)));
    }
}
