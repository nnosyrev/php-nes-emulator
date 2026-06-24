<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class SLOTest extends TestCase
{
    use CPUTestTrait;

    public function testSLO(): void
    {
        $this->loadProgramToRom([0xA9, 0b00000101, 0x85, 0x02, 0x07, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x02), 0b00001010);
        $this->assertSame($CPU->getRegisterA(), 0b00000101 | 0b00001010);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
