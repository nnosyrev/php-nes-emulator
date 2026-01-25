<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class PHPTest extends TestCase
{
    use CPUTestTrait;

    public function testPHPTrue(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagN(true);
        $CPU->setFlagV(true);
        $CPU->setFlagB(true);
        $CPU->setFlagD(true);
        $CPU->setFlagI(true);
        $CPU->setFlagZ(true);
        $CPU->setFlagC(true);
        $CPU->load([0x08, 0x00]);
        $CPU->run();

        $stackValue = $CPU->popFromStack()->value;

        $this->assertSame($stackValue & 0b10000000, 0b10000000);
        $this->assertSame($stackValue & 0b01000000, 0b01000000);
        $this->assertSame($stackValue & 0b00010000, 0b00010000);
        $this->assertSame($stackValue & 0b00001000, 0b00001000);
        $this->assertSame($stackValue & 0b00000100, 0b00000100);
        $this->assertSame($stackValue & 0b00000010, 0b00000010);
        $this->assertSame($stackValue & 0b00000001, 0b00000001);
    }

    public function testPHPFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagN(false);
        $CPU->setFlagV(false);
        $CPU->setFlagB(false);
        $CPU->setFlagD(false);
        $CPU->setFlagI(false);
        $CPU->setFlagZ(false);
        $CPU->setFlagC(false);
        $CPU->load([0x08, 0x00]);
        $CPU->run();

        $stackValue = $CPU->popFromStack()->value;

        $this->assertSame($stackValue | 0b01111111, 0b01111111);
        $this->assertSame($stackValue | 0b10111111, 0b10111111);
        $this->assertSame($stackValue | 0b11101111, 0b11101111);
        $this->assertSame($stackValue | 0b11110111, 0b11110111);
        $this->assertSame($stackValue | 0b11111011, 0b11111011);
        $this->assertSame($stackValue | 0b11111101, 0b11111101);
        $this->assertSame($stackValue | 0b11111110, 0b11111110);
    }
}
