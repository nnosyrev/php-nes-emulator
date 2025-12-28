<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\UInt8;

abstract class ROLAbstract implements InstructionInterface
{
    protected function getNew(CPU $CPU, UInt8 $old): UInt8
    {
        $old = $old->value;

        $new = $old << 1;
        $new = $CPU->getFlagC() ? $new | 0b00000001 : $new & 0b11111110;

        return new UInt8($new);
    }

    // TODO: !!!
    protected function setFlagC(CPU $CPU, UInt8 $old): void
    {
        $newCFlag = ($old->value & 0b10000000) === 0b10000000;

        $CPU->setFlagC($newCFlag);
    }
}
