<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Util\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ISCTest extends TestCase
{
    use CPUTestTrait;

    public function testISCZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0xA1, 0xE7, 0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x08, 0x05);
        $CPU->setFlagC(false);
        $CPU->run();

        $result = 0xA1 + (0x06 ^ 0xFF);

        // @phpstan-ignore greater.alwaysTrue
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);

        $result = $result % UInt8::BASE;

        $this->assertSame($CPU->getMemory(0x08), 0x06);
        $this->assertSame($CPU->getRegisterA(), $result);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), ((0xA1 ^ $result) & ($result ^ (0x06 ^ 0xFF)) & 0x80) !== 0);
    }
}
