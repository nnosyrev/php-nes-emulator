<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class DEYTest extends AbstractCPUTest
{
    public function testDEY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA0, 0x05, 0x88, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testDEYOverflow(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA0, 0x01, 0x88, 0x88, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0xFF);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }
}
