<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class BCSTest extends TestCase
{
    use CPUTestTrait;

    public function testBCS(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0xB0, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x06);
    }

    public function testBCSFlagCIsFalse(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0xB0, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }
}
