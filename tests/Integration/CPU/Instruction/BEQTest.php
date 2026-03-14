<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class BEQTest extends TestCase
{
    use CPUTestTrait;

    public function testBEQFlagZIsFalse(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0xF0, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }

    public function testBEQFlagZIsTrue(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x00, 0xF0, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x01);
    }
}
