<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use Tests\Integration\CPU\AbstractCPUTest;

final class BCSTest extends AbstractCPUTest
{
    public function testBCS(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagC(true);
        $CPU->load([0xE8, 0x00, 0xA2, 0x05, 0xB0, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x06);
    }

    public function testBCSFlagCIsFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagC(false);
        $CPU->load([0xE8, 0x00, 0xA2, 0x05, 0xB0, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }
}
