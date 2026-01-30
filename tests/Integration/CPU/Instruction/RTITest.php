<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class RTITest extends TestCase
{
    use CPUTestTrait;

    public function testRTITrue(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0x40, 0x00]);
        $CPU->pushToStackUInt16(new UInt16(0x8000 + 1));
        $CPU->pushToStack(new UInt8(0b11111111));
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagI(), true);
        $this->assertSame($CPU->getFlagD(), true);
        $this->assertSame($CPU->getFlagB(), true);
        $this->assertSame($CPU->getFlagV(), true);
        $this->assertSame($CPU->getFlagN(), true);
    }

    public function testRTIFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0x40, 0x00]);
        $CPU->pushToStackUInt16(new UInt16(0x8000 + 1));
        $CPU->pushToStack(new UInt8(0b00000000));
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagI(), false);
        $this->assertSame($CPU->getFlagD(), false);
        $this->assertSame($CPU->getFlagB(), false);
        $this->assertSame($CPU->getFlagV(), false);
        $this->assertSame($CPU->getFlagN(), false);
    }
}
