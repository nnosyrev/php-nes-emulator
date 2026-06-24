<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class DCPTest extends TestCase
{
    use CPUTestTrait;

    public function testDCPZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x85, 0x01, 0xC7, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x01), 0x05 - 1);
        // @phpstan-ignore greaterOrEqual.alwaysTrue
        $this->assertSame($CPU->getFlagC(), 0x05 >= 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue(0x05 - (0x05 - 1)));
    }
}
