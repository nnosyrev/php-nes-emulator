<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class SLO implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);
        $old = $CPU->getMemory($addr);

        $new = UInt8::shiftToLeft($old, 1);

        $CPU->setMemory($addr, $new);
        $CPU->setFlagC(($old & 0b10000000) === 0b10000000);
        $CPU->setRegisterA(UInt8::or($CPU->getRegisterA(), $new));
    }
}
