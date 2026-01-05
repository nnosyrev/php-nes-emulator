<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;

trait BranchTrait
{
    public function branch(bool $condition, CPU $cpu): void
    {
        if ($condition) {
            $displacement = $cpu->readMemory($cpu->getPC())->toInt8();

            $cpu
                ->incrementPC()
                ->addToPC($displacement);
        }
    }
}
