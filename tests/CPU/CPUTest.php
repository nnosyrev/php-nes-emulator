<?php

declare(strict_types=1);

namespace App\Tests\CPU;

use App\UInt16;

final class CPUTest extends AbstractCPUTest
{
    public function test5opcodes(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0xC0, 0xAA, 0xE8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0xC1);
    }

    public function testReadWriteMemoryUInt16(): void
    {
        $addr = new UInt16(0);

        $CPU = $this->CPU;
        $CPU->writeMemoryUInt16($addr, new UInt16(0x8000));

        $readed = $CPU->readMemoryUInt16($addr);

        $this->assertSame($readed->value, 0x8000);
    }
}
