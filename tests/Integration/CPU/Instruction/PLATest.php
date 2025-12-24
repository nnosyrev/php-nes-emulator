<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class PLATest extends AbstractCPUTest
{
    public function testPLA(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x48, 0xA9, 0x11, 0x68, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
