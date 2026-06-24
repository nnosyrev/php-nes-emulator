<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class RRA implements InstructionInterface
{
    use WithCarryTrait;

    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $old = $cpu->getMemory($addr);

        //$new = $old->shiftToRight(1);
        $new = UInt8::shiftToRight($old, 1);
        if ($cpu->getFlagC()) {
            //$new = $new->or(new UInt8(0b10000000));
            $new = UInt8::or($new, 0b10000000);
        }
        $cpu->setFlagC(($old & 0b00000001) === 0b00000001);

        $cpu->setMemory($addr, $new);

        $this->addToRegisterAWithCarry($new, $cpu);
    }
}
