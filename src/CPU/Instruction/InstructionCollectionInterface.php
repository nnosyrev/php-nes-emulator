<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

interface InstructionCollectionInterface
{
    public function get(string $class): InstructionInterface;
}
