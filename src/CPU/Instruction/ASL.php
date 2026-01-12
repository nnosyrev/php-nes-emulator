<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ASL implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);
        $old = $CPU->readMemory($addr);

        $new = $old->shiftToLeft(1);

        $CPU->writeMemory($addr, $new);

        $CPU->setFlagC(($old->value & 0b10000000) === 0b10000000);
        $CPU->setFlagsZNByValue($new);
    }
}
