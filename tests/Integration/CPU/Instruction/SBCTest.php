<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class SBCTest extends TestCase
{
    use CPUTestTrait;

    public function testSBCImmediate(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagC(false);
        $CPU->load([0xA9, 0xA1, 0xE9, 0x05, 0x00]);
        $CPU->run();

        $result = 0xA1 - 0x05 - 1;

        $this->assertSame($CPU->getRegisterA()->value, $result);
        $this->assertSame($CPU->getFlagC(), !($result > 0xFF));
        $this->assertSame($CPU->getFlagV(), ((0x05 ^ $result) & ($result ^ 0xA1) & 0x80) !== 0);
    }

    public function testSBCImmediateOverflow(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagC(true);
        $CPU->load([0xA9, 0x05, 0xE9, 0xA1, 0x00]);
        $CPU->run();

        $result = (0x05 - 0xA1 + 256) % 256;

        $this->assertSame($CPU->getRegisterA()->value, $result);
        $this->assertSame($CPU->getFlagC(), !($result > 0xFF));
        $this->assertSame($CPU->getFlagV(), ((0xA1 ^ $result) & ($result ^ 0x05) & 0x80) !== 0);
    }
}
