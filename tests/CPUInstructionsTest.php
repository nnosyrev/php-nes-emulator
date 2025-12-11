<?php

declare(strict_types=1);

namespace App\Tests;

use App\CPU;
use App\Opcodes;
use PHPUnit\Framework\TestCase;

final class CPUInstructionsTest extends TestCase
{
    public function testLDA(): void
    {
        $CPU = new CPU;
        $CPU->interpret([Opcodes::LDA, 0x05, Opcodes::BRK]);

        $this->assertSame($CPU->getRegisterA(), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAFlags(): void
    {
        $CPU = new CPU;
        $CPU->interpret([Opcodes::LDA, 0x00, Opcodes::BRK]);

        $this->assertSame($CPU->getRegisterA(), 0x00);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testTAX(): void
    {
        $CPU = new CPU;
        $CPU->interpret([Opcodes::LDA, 0x05, Opcodes::TAX, 0x00]);

        $this->assertSame($CPU->getRegisterX(), $CPU->getRegisterA());
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
    
    public function testLDX(): void
    {
        $CPU = new CPU;
        $CPU->interpret([Opcodes::LDX, 0x05, Opcodes::BRK]);

        $this->assertSame($CPU->getRegisterX(), 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testINX(): void
    {
        $CPU = new CPU;
        $CPU->interpret([Opcodes::LDX, 0x05, Opcodes::INX, Opcodes::BRK]);

        $this->assertSame($CPU->getRegisterX(), 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testINXOverflow(): void
    {
        $CPU = new CPU;
        $CPU->interpret([Opcodes::LDX, 0xFF, Opcodes::INX, Opcodes::INX, Opcodes::BRK]);

        $this->assertSame($CPU->getRegisterX(), 0x01);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    private function getFlagNValue(int $value): bool
    {
        return ($value & 0b10000000) === 0b10000000;
    }
}
