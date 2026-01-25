<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class BITTest extends TestCase
{
    use CPUTestTrait;

    public function testBITZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x80, 0x24, 0x00, 0x00]);
        $CPU->writeMemory(new UInt16(0x00), new UInt8(0x7F));
        $CPU->run();

        $this->assertSame($CPU->getFlagV(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }

    public function testBITAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x80, 0x2C, 0x10, 0x22, 0x00]);
        $CPU->writeMemory(new UInt16(0x2210), new UInt8(0x00));
        $CPU->run();

        $this->assertSame($CPU->getFlagV(), false);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagN(), false);
    }
}
