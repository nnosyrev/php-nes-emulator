<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class XAS implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $cpu->setSP($cpu->getRegisterX()->and($cpu->getRegisterA()));

        $high = $addr->shiftToRight(8)
            ->toUInt8();

        $result = $cpu->getSP()->and($high->increment());

        $cpu->setMemory($addr, $result);
    }
}
