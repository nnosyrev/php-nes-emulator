<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class AAX implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $result = $cpu->getRegisterA()->and($cpu->getRegisterX());

        $cpu->writeMemory($mode->getOperandAddress($cpu), $result);
    }
}
