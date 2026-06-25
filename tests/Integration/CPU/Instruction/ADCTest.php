<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Util\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ADCTest extends TestCase
{
    use CPUTestTrait;

    public function testADCImmediate(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x69, 0xA1, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->run();

        $result = 0x05 + 0xA1 + 1;

        $this->assertSame($CPU->getRegisterA(), $result);
        // @phpstan-ignore greater.alwaysFalse
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), ((0xA1 ^ $result) & ($result ^ 0x05) & 0x80) !== 0);
    }

    public function testADCImmediateOverflow(): void
    {
        $this->loadProgramToRom([0xA9, 0xC8, 0x69, 0xDC, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->run();

        $result = 0xC8 + 0xDC;

        $this->assertSame($CPU->getRegisterA(), $result % UInt8::BASE);
        // @phpstan-ignore greater.alwaysTrue
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), ((0xDC ^ $result) & ($result ^ 0xC8) & 0x80) !== 0);
    }
}
