<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class PHATest extends AbstractCPUTest
{
    public function testPHA(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x48, 0x00]);
        $CPU->run();

        $stackValue = $CPU->popFromStack()->value;

        $this->assertSame($stackValue, $CPU->getRegisterA()->value);
        $this->assertSame($stackValue, 0x05);
    }
}
