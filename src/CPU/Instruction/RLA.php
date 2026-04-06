<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Type\UInt8;

final class RLA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $old = $cpu->getMemory($addr);

        $new = $old->shiftToLeft(1);

        if ($cpu->getFlagC()) {
            $new = $new->or(new UInt8(0b00000001));
        }

        $cpu->setMemory($addr, $new);
        $cpu->setRegisterA($cpu->getRegisterA()->and($new));
        $cpu->setFlagC(($old->value & 0b10000000) === 0b10000000);
    }
}
