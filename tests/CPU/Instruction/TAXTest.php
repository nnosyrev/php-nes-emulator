<?php

declare(strict_types=1);

namespace App\Tests\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\UInt8;
use PHPUnit\Framework\TestCase;

final class TAXTest extends TestCase
{
    public function testTAX(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([0xA9, 0x05, 0xAA, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }

    private function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
