<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\Util\Int8;

trait BranchTrait
{
    public function branch(bool $condition, CPU $cpu): void
    {
        if ($condition) {
            $displacement = Int8::createFromInt($cpu->getMemory($cpu->getPC()));

            $cpu
                ->incrementPC()
                ->addToPC($displacement);
        }
    }
}
