<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class LAR implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->getMemory($addr);

        $result = UInt8::and($value, $cpu->getSP());

        $cpu->setRegisterA($result);
        $cpu->setRegisterX($result);
        $cpu->setSP($result);
    }
}
