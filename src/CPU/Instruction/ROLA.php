<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ROLA extends ROLAbstract
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $old = $CPU->getRegisterA();

        $new = $this->getNew($CPU, $old);

        $this->setFlagC($CPU, $old);

        $CPU->setRegisterA($new);
    }
}
