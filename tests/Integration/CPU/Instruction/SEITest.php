<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\CPU\Exception\BreakException;
use App\CPU\Instruction\BRK;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Instruction\InstructionInterface;
use App\CPU\Instruction\SEI;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class SEITest extends TestCase
{
    use CPUTestTrait;

    public function testSEI(): void
    {
        $brk = $this->createStub(InstructionInterface::class);
        $brk->method('execute')
            ->willThrowException(new BreakException());

        $instructionFactory = $this->createStub(InstructionFactoryInterface::class);
        $instructionFactory->method('make')
            ->willReturnMap([
                [SEI::class, new SEI()],
                [BRK::class, $brk],
            ]);

        $this->container->set(InstructionFactoryInterface::class, $instructionFactory);

        $this->loadProgramToRom([0x78, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagI(false);
        $CPU->run();

        $this->assertSame($CPU->getFlagI(), true);
    }
}
