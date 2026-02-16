<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class LAXTest extends TestCase
{
    use CPUTestTrait;

    public function testLAXZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA7, 0x05, 0x00]);
        $CPU->writeMemory(new UInt16(0x05), new UInt8(0x11));
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x11);
        $this->assertSame($CPU->getRegisterX()->value, 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
