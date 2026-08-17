<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\CPU;
use App\CPU\Exception\BreakException;
use App\CPU\Instruction\InstructionInterface;
use App\CPU\Mode\ModeInterface;

final class BRK implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        throw new BreakException('Break');
    }
}
