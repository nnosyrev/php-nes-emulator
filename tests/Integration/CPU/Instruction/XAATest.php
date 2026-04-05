<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class XAATest extends TestCase
{
    use CPUTestTrait;

    public function testXAAImmediate(): void
    {
        $this->loadProgramToRom([0xA2, 0b11111111, 0x8B, 0b00011000, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b11111111 & 0b00011000);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
