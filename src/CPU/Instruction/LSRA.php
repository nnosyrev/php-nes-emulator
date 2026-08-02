<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class LSRA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->getMemory($cpu->getPC(), dummy: true);

        $old = $cpu->getRegisterA();

        $new = $old >> 1;

        $cpu->setRegisterA($new);

        $cpu->setFlagC(($old & 0b00000001) === 0b00000001);
    }
}
