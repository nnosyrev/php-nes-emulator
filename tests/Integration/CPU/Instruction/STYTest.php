<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class STYTest extends TestCase
{
    use CPUTestTrait;

    public function testSTYZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA0, 0x05, 0x84, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x34))->value, 0x05);
    }

    public function testSTYZeroPageX(): void
    {
        $CPU = $this->CPU;
        $CPU->setRegisterX(new UInt8(0x01));
        $CPU->load([0xA0, 0x05, 0x94, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x35))->value, 0x05);
    }

    public function testSTYAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA0, 0x05, 0x8C, 0x34, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x0134))->value, 0x05);
    }
}
