<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class AAXTest extends TestCase
{
    use CPUTestTrait;

    public function testAAXZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA2, 0x11, 0x87, 0x34, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x34))->value, 0x05 & 0x11);
    }
}
