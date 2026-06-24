<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ASRTest extends TestCase
{
    use CPUTestTrait;

    public function testASR(): void
    {
        $this->loadProgramToRom([0xA9, 0b11111111, 0x4B, 0b00001111, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0b00000111);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
