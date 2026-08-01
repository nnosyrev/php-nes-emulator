<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class ASL implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU, true);

        $old = $CPU->getMemory($addr);

        $CPU->endTick();

        // Dummy write
        $CPU->setMemory($addr, $old, dummy: true);
        $CPU->endTick();

        $new = UInt8::shiftToLeft($old, 1);

        $CPU->setMemory($addr, $new);

        $CPU->setFlagC(($old & 0b10000000) === 0b10000000);
        $CPU->setFlagsZNByValue($new);
    }
}
