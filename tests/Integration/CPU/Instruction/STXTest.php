<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use Tests\Integration\CPU\AbstractCPUTest;

final class STXTest extends AbstractCPUTest
{
    public function testSTXZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0x86, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x34))->value, 0x05);
    }

    public function testSTXZeroPageY(): void
    {
        $CPU = $this->CPU;
        $CPU->setRegisterY(new UInt8(0x01));
        $CPU->load([0xA2, 0x05, 0x96, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x35))->value, 0x05);
    }

    public function testSTXAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA2, 0x05, 0x8E, 0x34, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0134))->value, 0x05);
    }
}
