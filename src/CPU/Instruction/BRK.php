<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Exception\BreakException;
use App\CPU\Mode\ModeInterface;

final class BRK implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        throw new BreakException('Break');
    }
}
