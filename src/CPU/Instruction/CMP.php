<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class CMP implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $a = $cpu->getRegisterA();

        $addr = $mode->getOperandAddress($cpu);
        $mem = $cpu->readMemory($addr);

        $cpu->setFlagC($a->value >= $mem->value);
        $cpu->setFlagsZNByValue($a->subtract($mem));
    }
}
