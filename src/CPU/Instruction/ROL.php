<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\UInt8;

final class ROL implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $old = $CPU->readMemory($addr)->value;

        $newCFlag = ($old & 0b10000000) === 0b10000000;

        $new = $old << 1;
        $new = $CPU->getFlagC() ? $new | 0b00000001 : $new & 0b11111110;

        $uint8 = new UInt8($new);

        $CPU->setFlagC($newCFlag);
        $CPU->setFlagsZNByValue($uint8);
        $CPU->writeMemory($addr, $uint8);
    }
}
