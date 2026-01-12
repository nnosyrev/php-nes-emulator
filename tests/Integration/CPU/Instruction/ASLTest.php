<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use Tests\Integration\CPU\AbstractCPUTest;

final class ASLTest extends AbstractCPUTest
{
    public function testASL(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0b00000101, 0x85, 0x02, 0x06, 0x02, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x02))->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x02))));
    }

    public function testASLA(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0b10000101, 0x0A, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
