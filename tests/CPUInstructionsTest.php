<?php

namespace App\Tests;

use App\CPU;
use PHPUnit\Framework\TestCase;

final class CPUInstructionsTest extends TestCase
{
    public function testLDA(): void
    {
        $CPU = new CPU;
        $CPU->interpret([0xA9, 0x05, 0x00]);

        $this->assertSame($CPU->getRegisterA(), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), ($CPU->getRegisterA() & 0b10000000) === 0b10000000);
    }

    public function testLDAFlags(): void
    {
        $CPU = new CPU;
        $CPU->interpret([0xA9, 0x00, 0x00]);

        $this->assertSame($CPU->getRegisterA(), 0x00);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), ($CPU->getRegisterA() & 0b10000000) === 0b10000000);
    }

    public function testTAX(): void
    {
        $CPU = new CPU;
        $CPU->interpret([0xA9, 0x05, 0xAA, 0x00]);

        $this->assertSame($CPU->getRegisterX(), $CPU->getRegisterA());
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), ($CPU->getRegisterX() & 0b10000000) === 0b10000000);
    }
    
    public function testLDX(): void
    {
        $CPU = new CPU;
        $CPU->interpret([0xA2, 0x05, 0x00]);

        $this->assertSame($CPU->getRegisterX(), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), ($CPU->getRegisterX() & 0b10000000) === 0b10000000);
    }

    public function testINX(): void
    {
        $CPU = new CPU;
        $CPU->interpret([0xA2, 0x05, 0xE8, 0x00]);

        $this->assertSame($CPU->getRegisterX(), 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), ($CPU->getRegisterX() & 0b10000000) === 0b10000000);
    }
}
