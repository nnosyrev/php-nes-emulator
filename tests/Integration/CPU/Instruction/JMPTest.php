<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class JMPTest extends TestCase
{
    use CPUTestTrait;

    public function testJMPAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x4C, 0x07, 0x80, 0xA9, 0x29, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
    }

    public function testJMPIndirect(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x6C, 0x10, 0x22, 0xA9, 0x29, 0x00]);
        $CPU->writeMemoryUInt16(new UInt16(0x2210), new UInt16(0x8007));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
    }
}
