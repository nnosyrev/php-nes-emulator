<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class TXATest extends TestCase
{
    use CPUTestTrait;

    public function testTXA(): void
    {
        $this->loadProgramToRom([0xA2, 0x05, 0x8A, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
