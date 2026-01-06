<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class CMPTest extends AbstractCPUTest
{
    public function testCMPImmediate1(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xC9, 0x04, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testCMPImmediate2(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xC9, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testCMPImmediate3(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x04, 0xC9, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), true);
    }
}
