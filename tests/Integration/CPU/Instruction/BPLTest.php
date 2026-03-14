<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class BPLTest extends TestCase
{
    use CPUTestTrait;

    public function testBPL(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0b01110010, 0x10, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0b01110010 + 1);
    }

    public function testBPLFlagNIsTrue(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0b11110010, 0x10, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0b11110010);
    }
}
