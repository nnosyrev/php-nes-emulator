<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\CPU\Exception\BreakException;
use App\CPU\Instruction\BRK;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Instruction\InstructionInterface;
use App\CPU\Instruction\PHA;
use App\CPU\Instruction\PLP;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class PLPTest extends TestCase
{
    use CPUTestTrait;

    public function testPLPTrue(): void
    {
        $brk = $this->createStub(InstructionInterface::class);
        $brk->method('execute')
            ->willThrowException(new BreakException());

        $instructionFactory = $this->createStub(InstructionFactoryInterface::class);
        $instructionFactory->method('make')
            ->willReturnMap([
                [PHA::class, new PHA()],
                [PLP::class, new PLP()],
                [BRK::class, $brk],
            ]);

        $this->container->set(InstructionFactoryInterface::class, $instructionFactory);

        $this->loadProgramToRom([0x48, 0x28, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterA(0b11111111);
        $CPU->run();

        $this->assertSame($CPU->getFlagN(), true);
        $this->assertSame($CPU->getFlagV(), true);
        $this->assertSame($CPU->getFlagB(), true);
        $this->assertSame($CPU->getFlagD(), true);
        $this->assertSame($CPU->getFlagI(), true);
        $this->assertSame($CPU->getFlagZ(), true);
        $this->assertSame($CPU->getFlagC(), true);
    }

    public function testPLPFalse(): void
    {
        $this->loadProgramToRom([0x48, 0x28, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterA(0b00000000);
        $CPU->run();

        $this->assertSame($CPU->getFlagN(), false);
        $this->assertSame($CPU->getFlagV(), false);
        $this->assertSame($CPU->getFlagB(), false);
        $this->assertSame($CPU->getFlagD(), false);
        $this->assertSame($CPU->getFlagI(), false);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagC(), false);
    }
}
