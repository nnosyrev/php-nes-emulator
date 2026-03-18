<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class SLO implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);
        $old = $CPU->getMemory($addr);

        $new = $old->shiftToLeft(1);

        $CPU->setMemory($addr, $new);
        $CPU->setFlagC(($old->value & 0b10000000) === 0b10000000);
        $CPU->setRegisterA($CPU->getRegisterA()->or($new));
    }
}
