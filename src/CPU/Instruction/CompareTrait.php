<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\UInt8;

trait CompareTrait
{
    public function compare(UInt8 $register, CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);
        $mem = $cpu->readMemory($addr);

        $cpu->setFlagC($register->value >= $mem->value);
        $cpu->setFlagsZNByValue($register->subtract($mem));
    }
}
