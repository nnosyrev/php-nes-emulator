<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ROR extends RORAbstract
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu, forceDummyRead: true);

        $old = $cpu->getMemory($addr);
        $cpu->endTick();

        $cpu->setMemory($addr, $old, dummy: true);
        $cpu->endTick();

        $new = $this->getNew($cpu, $old);

        $cpu->setMemory($addr, $new);

        $this->setFlagC($cpu, $old);

        $cpu->setFlagsZNByValue($new);
    }
}
