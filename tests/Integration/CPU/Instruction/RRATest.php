<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class RRATest extends TestCase
{
    use CPUTestTrait;

    public function testRRAZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x67, 0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x08, 0b01000001);
        $CPU->setFlagC(true);
        $CPU->run();

        $result = 0x05 + 0b10100000 + 1;

        $this->assertSame($CPU->getMemory(0x08), 0b10100000);
        $this->assertSame($CPU->getRegisterA(), $result);
        // @phpstan-ignore greater.alwaysFalse
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), ((0b10100000 ^ $result) & ($result ^ 0x05) & 0x80) !== 0);
    }
}
