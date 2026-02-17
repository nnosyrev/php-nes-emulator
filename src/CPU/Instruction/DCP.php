<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class DCP implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->readMemory($addr);

        $result = $value->decrement();

        $cpu->writeMemory($addr, $result);

        $cpu->setFlagC($cpu->getRegisterA()->value >= $result->value);
        $cpu->setFlagsZNByValue($cpu->getRegisterA()->subtract($result));
    }
}
