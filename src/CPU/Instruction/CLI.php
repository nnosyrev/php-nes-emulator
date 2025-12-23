<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class CLI implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $CPU->setFlagI(false);
    }
}
