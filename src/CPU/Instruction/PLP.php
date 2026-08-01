<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class PLP implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $CPU->getMemory($CPU->getPC()); // Dummy read
        $CPU->endTick();

        $CPU->setFlagsFromUInt8($CPU->popFromStack(), [CPU::FLAG_B]);
    }
}
