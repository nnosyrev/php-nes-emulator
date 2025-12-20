<?php

declare(strict_types=1);

namespace App\Tests\Integration\CPU\Instruction;

use App\Tests\Integration\CPU\AbstractCPUTest;

final class INXTest extends AbstractCPUTest
{
    public function testINX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testINXOverflow(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0xFF, 0xE8, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x01);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
