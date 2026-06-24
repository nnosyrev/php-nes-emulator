<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ASR implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->getMemory($addr);

        $new = $cpu->getRegisterA() & $value;

        $cpu->setFlagC(($new & 0b00000001) === 0b00000001);

        $cpu->setRegisterA($new >> 1);
    }
}
