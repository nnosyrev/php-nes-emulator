<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class BMITest extends TestCase
{
    use CPUTestTrait;
    
    public function testBMI(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xE8, 0x00, 0xA2, 0b11110010, 0x30, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0b11110010 + 1);
    }

    public function testBMIFlagNIsFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xE8, 0x00, 0xA2, 0b01110010, 0x30, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0b01110010);
    }
}
