<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class INC implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu, forceDummyRead: true);

        $old = $cpu->getMemory($addr);
        $cpu->endTick();

        $cpu->setMemory($addr, $old);
        $cpu->endTick();

        $inc = UInt8::increment($old);

        $cpu->setMemory($addr, $inc);

        $cpu->setFlagsZNByValue($inc);
    }
}
