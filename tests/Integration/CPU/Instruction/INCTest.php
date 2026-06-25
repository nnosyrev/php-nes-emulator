<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class INCTest extends TestCase
{
    use CPUTestTrait;

    public function testINCZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x85, 0x01, 0xE6, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x01), 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x01)));
    }

    public function testINCZeroPageX(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x01, 0x85, 0x02, 0xF6, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x02), 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x02)));
    }

    public function testINCAbsolute(): void
    {
        $this->loadProgramToRom([0xEE, 0x01, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0201, 0x04);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0201), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x0201)));
    }

    public function testINCAbsoluteX(): void
    {
        $this->loadProgramToRom([0xFE, 0x01, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0202, 0x04);
        $CPU->setRegisterX(0x01);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0202), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(0x0202)));
    }
}
