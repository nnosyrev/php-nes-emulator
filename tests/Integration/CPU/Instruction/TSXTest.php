<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class TSXTest extends AbstractCPUTest
{
    public function testTXS(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xBA, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getSP()->value, $CPU->getRegisterX()->value);
        $this->assertSame($CPU->getFlagZ(), $CPU->getRegisterX()->value === 0);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
