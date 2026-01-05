<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class BEQTest extends AbstractCPUTest
{
    public function testBEQFlagZIsFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xE8, 0x00, 0xA2, 0x05, 0xF0, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }

    public function testBEQFlagZIsTrue(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xE8, 0x00, 0xA2, 0x00, 0xF0, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x01);
    }
}
