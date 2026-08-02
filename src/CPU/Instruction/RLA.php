<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class RLA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu, forceDummyRead: true);

        $old = $cpu->getMemory($addr);
        $cpu->endTick();

        // Dummy write
        $cpu->setMemory($addr, $old, dummy: true);
        $cpu->endTick();

        $new = UInt8::shiftToLeft($old, 1);

        if ($cpu->getFlagC()) {
            $new = UInt8::or($new, 0b00000001);
        }

        $cpu->setMemory($addr, $new);
        $cpu->setRegisterA(UInt8::and($cpu->getRegisterA(), $new));
        $cpu->setFlagC(($old & 0b10000000) === 0b10000000);
    }
}
