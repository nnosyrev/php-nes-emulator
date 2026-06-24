<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\Type\UInt8;

abstract class RORAbstract implements InstructionInterface
{
    protected function getNew(CPU $CPU, int /* UInt8 */ $old): int /* UInt8 */
    {
        $newValue = $old >> 1;
        $newValue = $CPU->getFlagC() ? $newValue | 0b10000000 : $newValue & 0b01111111;

        return $newValue;
    }

    // TODO: !!!
    protected function setFlagC(CPU $CPU, int /* UInt8 */ $old): void
    {
        $newCFlag = ($old & 0b00000001) === 0b00000001;

        $CPU->setFlagC($newCFlag);
    }
}
