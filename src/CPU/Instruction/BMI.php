<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class BMI implements InstructionInterface
{
    use BranchTrait;

    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $this->branch($cpu->getFlagN(), $cpu);
    }
}
