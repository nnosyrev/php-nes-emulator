<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class SBCTest extends TestCase
{
    use CPUTestTrait;

    public function testSBCImmediate(): void
    {
        $this->loadProgramToRom([0xA9, 0xA1, 0xE9, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->run();

        $result = 0xA1 + (0x05 ^ 0xFF);

        // @phpstan-ignore greater.alwaysTrue
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);

        $result = $result % UInt8::BASE;

        $this->assertSame($CPU->getRegisterA(), $result);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), ((0xA1 ^ $result) & ($result ^ (0x05 ^ 0xFF)) & 0x80) !== 0);
    }

    public function testSBCImmediate2(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xE9, 0xA1, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->run();

        $result = 0x05 + (0xA1 ^ 0xFF) + 1;

        // @phpstan-ignore greater.alwaysFalse
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);

        $result = $result % UInt8::BASE;

        $this->assertSame($CPU->getRegisterA(), $result);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), (((0xA1 ^ 0xFF) ^ $result) & ($result ^ 0x05) & 0x80) !== 0);
    }
}
