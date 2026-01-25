<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class PLPTest extends TestCase
{
    use CPUTestTrait;

    public function testPLPTrue(): void
    {
        $CPU = $this->CPU;
        $CPU->setRegisterA(new UInt8(0b11111111));
        $CPU->load([0x48, 0x28, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagN(), true);
        $this->assertSame($CPU->getFlagV(), true);
        $this->assertSame($CPU->getFlagB(), true);
        $this->assertSame($CPU->getFlagD(), true);
        $this->assertSame($CPU->getFlagI(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagC(), true);
    }

    public function testPLPFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->setRegisterA(new UInt8(0b00000000));
        $CPU->load([0x48, 0x28, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagN(), false);
        $this->assertSame($CPU->getFlagV(), false);
        $this->assertSame($CPU->getFlagB(), false);
        $this->assertSame($CPU->getFlagD(), false);
        $this->assertSame($CPU->getFlagI(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagC(), false);
    }
}
