<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class SRETest extends TestCase
{
    use CPUTestTrait;

    public function testSRE(): void
    {
        $this->loadProgramToRom([0xA9, 0b00010100, 0x85, 0x02, 0x47, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x02), 0b00001010);
        $this->assertSame($CPU->getRegisterA(), 0b00001010 ^ 0b00010100);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
