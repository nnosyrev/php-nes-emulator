<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class RRATest extends TestCase
{
    use CPUTestTrait;

    public function testRRAZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x67, 0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x08), new UInt8(0b01000001));
        $CPU->setFlagC(true);
        $CPU->run();

        $result = 0x05 + 0b10100000 + 1;

        $this->assertSame($CPU->getMemory(new UInt16(0x08))->value, 0b10100000);
        $this->assertSame($CPU->getRegisterA()->value, $result);
        // @phpstan-ignore greater.alwaysFalse
        $this->assertSame($CPU->getFlagC(), $result > 0xFF);
        // @phpstan-ignore notIdentical.alwaysFalse
        $this->assertSame($CPU->getFlagV(), ((0b10100000 ^ $result) & ($result ^ 0x05) & 0x80) !== 0);
    }
}
