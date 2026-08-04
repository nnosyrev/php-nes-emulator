<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class AXS implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->getMemory($addr);

        $cpu->setRegisterX($cpu->getRegisterX() & $cpu->getRegisterA());

        $cpu->setFlagC($value <= $cpu->getRegisterX());

        $cpu->setRegisterX(UInt8::subtract($cpu->getRegisterX(), $value));
    }
}
