<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\CPU\Exception\BreakException;
use App\CPU\Instruction\BRK;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Instruction\InstructionInterface;
use App\CPU\Instruction\RTI;
use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class RTITest extends TestCase
{
    use CPUTestTrait;

    public function testRTITrue(): void
    {
        $brk = $this->createStub(InstructionInterface::class);
        $brk->method('execute')
            ->willThrowException(new BreakException());

        $instructionFactory = $this->createStub(InstructionFactoryInterface::class);
        $instructionFactory->method('make')
            ->willReturnMap([
                [RTI::class, new RTI()],
                [BRK::class, $brk],
            ]);

        $this->container->set(InstructionFactoryInterface::class, $instructionFactory);

        $this->loadProgramToRom([0x40, 0x00]);

        $CPU = $this->getCpu();
        $CPU->pushToStackUInt16(new UInt16(0x8000 + 1));
        $CPU->pushToStack(new UInt8(0b11111111));
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagI(), true);
        $this->assertSame($CPU->getFlagD(), true);
        $this->assertSame($CPU->getFlagB(), true);
        $this->assertSame($CPU->getFlagV(), true);
        $this->assertSame($CPU->getFlagN(), true);
    }

    public function testRTIFalse(): void
    {
        $this->loadProgramToRom([0x40, 0x00]);

        $CPU = $this->getCpu();
        $CPU->pushToStackUInt16(new UInt16(0x8000 + 1));
        $CPU->pushToStack(new UInt8(0b00000000));
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagI(), false);
        $this->assertSame($CPU->getFlagD(), false);
        $this->assertSame($CPU->getFlagB(), false);
        $this->assertSame($CPU->getFlagV(), false);
        $this->assertSame($CPU->getFlagN(), false);
    }
}
