<?php

declare(strict_types=1);

namespace Tests\Integration\CPU;

use App\Bus;
use App\CPU\CPU;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\Type\UInt8;

trait CPUTestTrait
{
    private CPU $CPU;

    protected function setUp(): void
    {
        $this->CPU = new CPU(
            new Bus(),
            new OpcodeCollection(),
            new InstructionFactory(),
            new ModeFactory()
        );
    }

    protected function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
