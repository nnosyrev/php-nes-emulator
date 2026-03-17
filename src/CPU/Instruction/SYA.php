<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class SYA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $high = $addr->shiftToRight(8)->toUInt8();

        $result = $cpu->getRegisterY()->and($high->increment());

        $cpu->setMemory($addr, $result);
    }
}
