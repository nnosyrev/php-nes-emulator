<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class RLATest extends TestCase
{
    use CPUTestTrait;

    public function testRLAZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0b00000101, 0x85, 0x02, 0x27, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x02), 0b00001011);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
        $this->assertSame($CPU->getRegisterA(), 0b00000101 & 0b00001011);
    }
}
