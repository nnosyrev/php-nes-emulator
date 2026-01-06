<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class CPY implements InstructionInterface
{
    use CompareTrait;

    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $this->compare($cpu->getRegisterY(), $cpu, $mode);
    }
}
