<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class SRE implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);
        $old = $CPU->readMemory($addr);

        $new = $old->shiftToRight(1);

        $CPU->writeMemory($addr, $new);

        $CPU->setFlagC(($old->value & 0b00000001) === 0b00000001);
        $CPU->setFlagsZNByValue($new);

        $CPU->setRegisterA($new->xor($CPU->getRegisterA()));
    }
}
