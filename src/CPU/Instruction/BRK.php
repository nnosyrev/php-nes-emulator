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
        // In the PHPTest, PLPTest, RTITest and SEITest, a stub of this class
        // is created to disable the check (because the I flag appears in them)
        if (!$CPU->getFlagI()) {
            throw new BreakException('Break');
        }
    }
}
