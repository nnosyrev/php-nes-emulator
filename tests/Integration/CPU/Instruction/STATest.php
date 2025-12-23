<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use Tests\Integration\CPU\AbstractCPUTest;

final class STATest extends AbstractCPUTest
{
    public function testSTAZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x85, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x34))->value, 0x05);
    }

    public function testSTAZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA2, 0x01, 0x95, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x35))->value, 0x05);
    }

    public function testSTAAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x8D, 0x34, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0134))->value, 0x05);
    }

    public function testSTAAbsoluteX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA2, 0x01, 0x9D, 0x34, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0135))->value, 0x05);
    }

    public function testSTAAbsoluteY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA0, 0x01, 0x99, 0x34, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0135))->value, 0x05);
    }

    public function testSTAIndirectX(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA2, 0x01, 0x81, 0x34, 0x00]);
        $CPU->writeMemory(new UInt16(0x35), new UInt8(0x02));
        $CPU->writeMemory(new UInt16(0x36), new UInt8(0x01));
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0102))->value, 0x05);
    }

    public function testSTAIndirectY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA0, 0x01, 0x91, 0x34, 0x00]);
        $CPU->writeMemory(new UInt16(0x34), new UInt8(0x02));
        $CPU->writeMemory(new UInt16(0x35), new UInt8(0x01));
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0103))->value, 0x05);
    }
}
