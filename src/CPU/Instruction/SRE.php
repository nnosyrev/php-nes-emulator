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

        $new = UInt8::shiftToRight($old, 1);

        $CPU->setMemory($addr, $new);

        $CPU->endCycle();

        $CPU->setFlagC(($old & 0b00000001) === 0b00000001);
        $CPU->setFlagsZNByValue($new);

        $CPU->setRegisterA(UInt8::xor($new, $CPU->getRegisterA()));
    }
}
