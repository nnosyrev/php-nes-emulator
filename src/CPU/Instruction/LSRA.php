<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class LSRA implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $old = $CPU->getRegisterA();

        $new = $old->shiftToRight(1);

        $CPU->setRegisterA($new);

        $CPU->setFlagC(($old->value & 0b00000001) === 0b00000001);
        $CPU->setFlagsZNByValue($new);
    }
}
