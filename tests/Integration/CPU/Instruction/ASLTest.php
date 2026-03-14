<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ASLTest extends TestCase
{
    use CPUTestTrait;

    public function testASL(): void
    {
        $this->loadProgramToRom([0xA9, 0b00000101, 0x85, 0x02, 0x06, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x02))->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(new UInt16(0x02))));
    }

    public function testASLA(): void
    {
        $this->loadProgramToRom([0xA9, 0b10000101, 0x0A, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
