<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class ASLA implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $old = $CPU->getRegisterA();

        $new = UInt8::shiftToLeft($old, 1);

        $CPU->setRegisterA($new);

        $CPU->setFlagC(($old & 0b10000000) === 0b10000000);
        $CPU->setFlagsZNByValue($new);
    }
}
