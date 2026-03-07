<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class INC implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $orig = $CPU->getMemory($addr);
        $inc = $orig->increment();

        $CPU->setMemory($addr, $inc);

        $CPU->setFlagsZNByValue($inc);
    }
}
