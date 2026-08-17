<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

interface InstructionFactoryInterface
{
    public function make(string $class): InstructionInterface;
}
