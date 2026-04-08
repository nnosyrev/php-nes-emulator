<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class AXS implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->getMemory($addr);

        $cpu->setRegisterX($cpu->getRegisterX()->and($cpu->getRegisterA()));

        if ($value->value <= $cpu->getRegisterX()->value) {
            $cpu->setFlagC(true);
        }

        $cpu->setRegisterX($cpu->getRegisterX()->subtract($value));
    }
}
