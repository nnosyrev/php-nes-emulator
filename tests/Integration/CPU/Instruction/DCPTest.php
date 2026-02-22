<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\UInt16;
use App\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class DCPTest extends TestCase
{
    use CPUTestTrait;

    public function testDCPZeroPage(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x85, 0x01, 0xC7, 0x01, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->readMemory(new UInt16(0x01))->value, 0x05 - 1);
        // @phpstan-ignore greaterOrEqual.alwaysTrue
        $this->assertSame($CPU->getFlagC(), 0x05 >= 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue(new UInt8(0x05 - (0x05 - 1))));
    }
}
