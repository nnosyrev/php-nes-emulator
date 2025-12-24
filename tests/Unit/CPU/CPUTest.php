<?php

declare(strict_types=1);

namespace Tests\Unit\CPU;

use App\CPU\CPU;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;

final class CPUTest extends TestCase
{
    public function testReadWriteMemoryUInt16(): void
    {
        $addr = new UInt16(0);

        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->writeMemoryUInt16($addr, new UInt16(0x8000));

        $readed = $CPU->readMemoryUInt16($addr);

        $this->assertSame($readed->value, 0x8000);
    }

    public function testPushPopStack(): void
    {
        $CPU = new CPU(new OpcodeCollection(), new InstructionFactory(), new ModeFactory());
        $CPU->load([]);
        $CPU->pushToStack(new UInt8(1));
        $CPU->pushToStack(new UInt8(2));
        $CPU->pushToStack(new UInt8(3));

        $this->assertSame($CPU->popFromStack()->value, 3);
        $this->assertSame($CPU->popFromStack()->value, 2);
        $this->assertSame($CPU->popFromStack()->value, 1);
    }
}
