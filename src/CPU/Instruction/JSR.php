<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Type\UInt8;
use App\Util\UInt16;

final class JSR implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        //$cpu->pushToStackUInt16($cpu->getPC()->add(new UInt8(2))->subtract(new UInt8(1)));
        $cpu->pushToStackUInt16(UInt16::subtract(UInt16::add($cpu->getPC(), 2), 1));

        $addr = $mode->getOperandAddress($cpu);

        $cpu->setPC($addr);
    }
}
