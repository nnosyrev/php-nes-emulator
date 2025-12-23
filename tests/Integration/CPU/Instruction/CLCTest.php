<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class CLCTest extends AbstractCPUTest
{
    public function testCLC(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagC(true);
        $CPU->load([0x18, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
    }
}
