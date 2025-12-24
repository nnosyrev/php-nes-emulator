<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class PLA implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $CPU->setRegisterA($CPU->popFromStack());
    }
}
