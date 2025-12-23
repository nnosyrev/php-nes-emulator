<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class CLVTest extends AbstractCPUTest
{
    public function testCLV(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagV(true);
        $CPU->load([0xB8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagV(), false);
    }
}
