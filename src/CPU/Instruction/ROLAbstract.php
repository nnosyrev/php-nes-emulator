<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\Util\UInt8;

abstract class ROLAbstract implements InstructionInterface
{
    protected function getNew(CPU $CPU, int /* UInt8 */ $old): int /* UInt8 */
    {
        $newValue = UInt8::shiftToLeft($old, 1);
        $newValue = $CPU->getFlagC() ? $newValue | 0b00000001 : $newValue & 0b11111110;

        return $newValue;
    }

    protected function setFlagC(CPU $CPU, int /* UInt8 */ $old): void
    {
        $newCFlag = ($old & 0b10000000) === 0b10000000;

        $CPU->setFlagC($newCFlag);
    }
}
