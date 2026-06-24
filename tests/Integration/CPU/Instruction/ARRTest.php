<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ARRTest extends TestCase
{
    use CPUTestTrait;

    public function testARRFlagCTrue(): void
    {
        $this->loadProgramToRom([0xA9, 0b01000100, 0x6B, 0b00001100, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->run();

        // 0b01000100
        // 0b00001100
        // 0b00000100
        // 0b10000010

        $this->assertSame($CPU->getRegisterA(), ((0b01000100 & 0b00001100) >> 1) | 0b10000000);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagV(), false);
    }

    public function testARRFlagCFalse(): void
    {
        $this->loadProgramToRom([0xA9, 0b11000100, 0x6B, 0b10001100, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->run();

        // 0b11000100
        // 0b10001100
        // 0b10000100
        // 0b01000010
        //    6

        $this->assertSame($CPU->getRegisterA(), (0b11000100 & 0b10001100) >> 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagV(), true);
    }
}
