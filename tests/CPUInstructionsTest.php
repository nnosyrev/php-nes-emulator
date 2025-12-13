<?php

declare(strict_types=1);

namespace App\Tests;

use App\CPU;
use App\Opcodes;
use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;

final class CPUInstructionsTest extends TestCase
{
    public function testLDA(): void
    {
        $CPU = new CPU;
        $CPU->load([Opcodes::LDA, 0x05, Opcodes::BRK]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testLDAFlags(): void
    {
        $CPU = new CPU;
        $CPU->load([Opcodes::LDA, 0x00, Opcodes::BRK]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x00);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }

    public function testTAX(): void
    {
        $CPU = new CPU;
        $CPU->load([Opcodes::LDA, 0x05, Opcodes::TAX, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
    
    public function testLDX(): void
    {
        $CPU = new CPU;
        $CPU->load([Opcodes::LDX, 0x05, Opcodes::BRK]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testINX(): void
    {
        $CPU = new CPU;
        $CPU->load([Opcodes::LDX, 0x05, Opcodes::INX, Opcodes::BRK]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testINXOverflow(): void
    {
        $CPU = new CPU;
        $CPU->load([Opcodes::LDX, 0xFF, Opcodes::INX, Opcodes::INX, Opcodes::BRK]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x01);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function test5opcodes(): void
    {
        $CPU = new CPU;
        $CPU->load([0xA9, 0xC0, 0xAA, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0xC1);
    }

    public function testReadWriteMemoryUInt16(): void
    {
        $addr = new UInt16(0);

        $CPU = new CPU;
        $CPU->writeMemoryUInt16($addr, new UInt16(0x8000));

        $readed = $CPU->readMemoryUInt16($addr);

        $this->assertSame($readed->value, 0x8000);
    }

    private function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
