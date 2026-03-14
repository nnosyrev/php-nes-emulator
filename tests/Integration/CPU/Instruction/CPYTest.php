<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class CPYTest extends TestCase
{
    use CPUTestTrait;

    public function testCPYImmediate1(): void
    {
        $this->loadProgramToRom([0xA0, 0x05, 0xC0, 0x04, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testCPYImmediate2(): void
    {
        $this->loadProgramToRom([0xA0, 0x05, 0xC0, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testCPYImmediate3(): void
    {
        $this->loadProgramToRom([0xA0, 0x04, 0xC0, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), true);
    }
}
