<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class RORA extends RORAbstract
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->getMemory($cpu->getPC(), dummy: true);

        $old = $cpu->getRegisterA();

        $new = $this->getNew($cpu, $old);

        $this->setFlagC($cpu, $old);

        $cpu->setRegisterA($new);
    }
}
