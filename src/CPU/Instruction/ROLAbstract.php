<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\UInt8;

abstract class ROLAbstract implements InstructionInterface
{
    protected function getNew(CPU $CPU, UInt8 $old): UInt8
    {
        $newValue = $old->shiftToLeft(1)->value;
        $newValue = $CPU->getFlagC() ? $newValue | 0b00000001 : $newValue & 0b11111110;

        return new UInt8($newValue);
    }

    // TODO: !!!
    protected function setFlagC(CPU $CPU, UInt8 $old): void
    {
        $newCFlag = ($old->value & 0b10000000) === 0b10000000;

        $CPU->setFlagC($newCFlag);
    }
}
