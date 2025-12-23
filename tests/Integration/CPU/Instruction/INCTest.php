<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use Tests\Integration\CPU\AbstractCPUTest;

final class INCTest extends AbstractCPUTest
{
    public function testINCZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x85, 0x01, 0xE6, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x01))->value, 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x01))));
    }

    public function testINCZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA2, 0x01, 0x85, 0x02, 0xF6, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x02))->value, 0x05 + 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x02))));
    }

    public function testINCAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->writeMemory(new UInt16(0x0201), new UInt8(0x04));
        $CPU->load([0xEE, 0x01, 0x02, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0201))->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x0201))));
    }

    public function testINCAbsoluteX(): void
    {
        $CPU = $this->CPU;
        $CPU->writeMemory(new UInt16(0x0202), new UInt8(0x04));
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->load([0xFE, 0x01, 0x02, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0202))->value, 0x05);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->readMemory(new UInt16(0x0202))));
    }
}
