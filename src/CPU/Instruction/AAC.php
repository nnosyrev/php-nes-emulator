<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class AAC implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $data = $cpu->readMemory($addr);

        $cpu->setRegisterA($data->and($cpu->getRegisterA()));

        $cpu->setFlagC($cpu->getFlagN());
    }
}
