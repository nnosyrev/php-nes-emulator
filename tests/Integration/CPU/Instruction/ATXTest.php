<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ATXTest extends TestCase
{
    use CPUTestTrait;

    public function testATX(): void
    {
        $this->loadProgramToRom([0xA9, 0b11111111, 0xAB, 0b00001111, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b00001111);
        $this->assertSame($CPU->getRegisterX()->value, 0b00001111);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
