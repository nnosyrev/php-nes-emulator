<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class CLITest extends AbstractCPUTest
{
    public function testCLI(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagI(true);
        $CPU->load([0x58, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagI(), false);
    }
}
