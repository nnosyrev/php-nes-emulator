<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class BITTest extends TestCase
{
    use CPUTestTrait;

    public function testBITZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x80, 0x24, 0x00, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x00, 0x7F);
        $CPU->run();

        $this->assertSame($CPU->getFlagV(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testBITAbsolute(): void
    {
        $this->loadProgramToRom([0xA9, 0x80, 0x2C, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x0210, 0x00);
        $CPU->run();

        $this->assertSame($CPU->getFlagV(), false);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }
}
