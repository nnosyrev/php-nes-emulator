<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class DEC implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu, forceDummyRead: true);

        $value = $cpu->getMemory($addr);

        $cpu->setMemory($addr, $value, dummy: true);
        $cpu->endTick();

        $dec = UInt8::decrement($value);

        $cpu->setMemory($addr, $dec);

        $cpu->setFlagsZNByValue($dec);
    }
}
