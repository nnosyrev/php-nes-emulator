<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\CPU\Exception\BreakException;
use App\CPU\Instruction\BRK;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Instruction\InstructionInterface;
use App\CPU\Instruction\PHP;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class PHPTest extends TestCase
{
    use CPUTestTrait;

    public function testPHPTrue(): void
    {
        $brk = $this->createStub(InstructionInterface::class);
        $brk->method('execute')
            ->willThrowException(new BreakException());

        $instructionFactory = $this->createStub(InstructionFactoryInterface::class);
        $instructionFactory->method('make')
            ->willReturnMap([
                [PHP::class, new PHP()],
                [BRK::class, $brk],
            ]);

        $this->container->set(InstructionFactoryInterface::class, $instructionFactory);

        $this->loadProgramToRom([0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagN(true);
        $CPU->setFlagV(true);
        $CPU->setFlagB(true);
        $CPU->setFlagD(true);
        $CPU->setFlagI(true);
        $CPU->setFlagZ(true);
        $CPU->setFlagC(true);
        $CPU->run();

        $stackValue = $CPU->popFromStack();

        $this->assertSame($stackValue & 0b10000000, 0b10000000);
        $this->assertSame($stackValue & 0b01000000, 0b01000000);
        $this->assertSame($stackValue & 0b00010000, 0b00010000);
        $this->assertSame($stackValue & 0b00001000, 0b00001000);
        $this->assertSame($stackValue & 0b00000100, 0b00000100);
        $this->assertSame($stackValue & 0b00000010, 0b00000010);
        $this->assertSame($stackValue & 0b00000001, 0b00000001);
    }

    public function testPHPFalse(): void
    {
        $this->loadProgramToRom([0x08, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagN(false);
        $CPU->setFlagV(false);
        $CPU->setFlagB(false);
        $CPU->setFlagD(false);
        $CPU->setFlagI(false);
        $CPU->setFlagZ(false);
        $CPU->setFlagC(false);
        $CPU->run();

        $stackValue = $CPU->popFromStack();

        $this->assertSame($stackValue | 0b01111111, 0b01111111);
        $this->assertSame($stackValue | 0b10111111, 0b10111111);
        $this->assertSame($stackValue | 0b11101111, 0b11101111);
        $this->assertSame($stackValue | 0b11110111, 0b11110111);
        $this->assertSame($stackValue | 0b11111011, 0b11111011);
        $this->assertSame($stackValue | 0b11111101, 0b11111101);
        $this->assertSame($stackValue | 0b11111110, 0b11111110);
    }
}
