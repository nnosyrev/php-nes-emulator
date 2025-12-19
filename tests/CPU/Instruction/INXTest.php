<?php

declare(strict_types=1);

namespace App\Tests\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\UInt8;
use PHPUnit\Framework\TestCase;

final class INXTest extends TestCase
{
    public function testINX(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA2, 0x05, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    public function testINXOverflow(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA2, 0xFF, 0xE8, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x01);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    private function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
