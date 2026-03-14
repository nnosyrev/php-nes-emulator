<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class ROLTest extends TestCase
{
    use CPUTestTrait;

    public function testROL(): void
    {
        $this->loadProgramToRom([0xA9, 0b00000101, 0x85, 0x02, 0x26, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x02))->value, 0b00001011);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(new UInt16(0x02))));
    }

    public function testROLA(): void
    {
        $this->loadProgramToRom([0xA9, 0b10000101, 0x2A, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
