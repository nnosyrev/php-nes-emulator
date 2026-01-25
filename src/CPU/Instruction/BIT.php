<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class BIT implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->readMemory($addr);

        $cpu->setFlagNByValue($value);
        $cpu->setFlagZByValue($value->and($cpu->getRegisterA()));
        $cpu->setFlagV(($value->value & 0b01000000) === 0b01000000);
    }
}
