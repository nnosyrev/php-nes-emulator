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
        // TODO: To fix instructions tests.
        // In the PHPTest, PLPTest, RTITest and SEITest, a stub of this class
        // is created to disable the check (because the I flag appears in them)
        if (!$cpu->getFlagI()) {
            throw new BreakException('Break');
        }
    }
}
