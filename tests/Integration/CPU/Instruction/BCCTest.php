<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class BCCTest extends TestCase
{
    use CPUTestTrait;

    public function testBCC(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0x90, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x06);
    }

    public function testBCCFlagCIsTrue(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0x90, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }
}
