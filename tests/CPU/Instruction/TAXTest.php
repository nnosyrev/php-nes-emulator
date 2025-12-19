<?php

declare(strict_types=1);

namespace App\Tests\CPU\Instruction;

use App\Tests\CPU\AbstractCPUTest;

final class TAXTest extends AbstractCPUTest
{
    public function testTAX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xAA, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
