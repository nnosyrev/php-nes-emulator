<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class TXSTest extends AbstractCPUTest
{
    public function testTXS(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0x9A, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getSP()->value, $CPU->getRegisterX()->value);
        $this->assertSame($CPU->getSP()->value, 0x05);
    }
}
