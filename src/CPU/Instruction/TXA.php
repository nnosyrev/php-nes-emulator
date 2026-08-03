<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class TXA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->getMemory($cpu->getPC(), dummy: true);

        $cpu->setRegisterA($cpu->getRegisterX());
    }
}
