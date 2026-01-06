<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class CPX implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $x = $cpu->getRegisterX();

        $addr = $mode->getOperandAddress($cpu);
        $mem = $cpu->readMemory($addr);

        $cpu->setFlagC($x->value >= $mem->value);
        $cpu->setFlagsZNByValue($x->subtract($mem));
    }
}
