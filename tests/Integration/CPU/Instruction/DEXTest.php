<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class DEXTest extends AbstractCPUTest
{
    public function testDEX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0xCA, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testDEXOverflow(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x01, 0xCA, 0xCA, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0xFF);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
