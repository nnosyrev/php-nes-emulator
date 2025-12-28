<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class ROLATest extends AbstractCPUTest
{
    public function testROLA(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagC(false);
        $CPU->load([0xA9, 0b10000101, 0x2A, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
