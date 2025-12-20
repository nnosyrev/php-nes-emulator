<?php

declare(strict_types=1);

namespace Tests\Integration\CPU;

final class CPUTest extends AbstractCPUTest
{
    public function test5opcodes(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0xC0, 0xAA, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0xC1);
    }
}
