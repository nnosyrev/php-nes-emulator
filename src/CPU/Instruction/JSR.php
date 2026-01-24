<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\UInt8;

final class JSR implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->pushToStackUInt16($cpu->getPC()->add(new UInt8(2))->subtract(new UInt8(1)));

        $addr = $mode->getOperandAddress($cpu);

        $cpu->setPC($addr);
    }
}
