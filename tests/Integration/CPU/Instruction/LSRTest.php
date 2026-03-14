<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class LSRTest extends TestCase
{
    use CPUTestTrait;

    public function testLSR(): void
    {
        $this->loadProgramToRom([0xA9, 0b00010100, 0x85, 0x02, 0x46, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x02))->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getMemory(new UInt16(0x02))));
    }

    public function testLSRA(): void
    {
        $this->loadProgramToRom([0xA9, 0b00010101, 0x4A, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0b00001010);
        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
