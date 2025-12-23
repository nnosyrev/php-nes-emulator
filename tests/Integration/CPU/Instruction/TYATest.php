<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class TYATest extends AbstractCPUTest
{
    public function testTYA(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA0, 0x05, 0x98, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
