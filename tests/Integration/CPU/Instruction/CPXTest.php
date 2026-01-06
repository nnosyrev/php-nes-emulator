<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class CPXTest extends AbstractCPUTest
{
    public function testCPXImmediate1(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0xE0, 0x04, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testCPXImmediate2(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0xE0, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testCPXImmediate3(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x04, 0xE0, 0x05, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), true);
    }
}
