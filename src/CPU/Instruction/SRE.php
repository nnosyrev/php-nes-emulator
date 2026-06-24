<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class SRE implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);
        $old = $CPU->getMemory($addr);

        //$new = $old->shiftToRight(1);
        $new = UInt8::shiftToRight($old, 1);

        $CPU->setMemory($addr, $new);

        $CPU->setFlagC(($old & 0b00000001) === 0b00000001);
        $CPU->setFlagsZNByValue($new);

        //$CPU->setRegisterA($new->xor($CPU->getRegisterA()));
        $CPU->setRegisterA(UInt8::xor($new, $CPU->getRegisterA()));
    }
}
