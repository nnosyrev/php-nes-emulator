<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class TXATest extends AbstractCPUTest
{
    public function testTXA(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0x8A, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
