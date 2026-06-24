<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

trait CompareTrait
{
    public function compare(int /* UInt8 */ $register, CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);
        $mem = $cpu->getMemory($addr);

        $cpu->setFlagC($register >= $mem);
        $cpu->setFlagsZNByValue(UInt8::subtract($register, $mem));
    }
}
