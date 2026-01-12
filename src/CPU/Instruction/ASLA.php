<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ASLA implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $old = $CPU->getRegisterA();

        $new = $old->shiftToLeft(1);

        $CPU->setRegisterA($new);

        $CPU->setFlagC(($old->value & 0b10000000) === 0b10000000);
        $CPU->setFlagsZNByValue($new);
    }
}
