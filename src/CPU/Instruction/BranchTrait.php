<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\Util\Int8;

trait BranchTrait
{
    public function branch(bool $condition, CPU $cpu): void
    {
        $operand = $cpu->getMemory($cpu->getPC());

        if ($condition) {
            $displacement = Int8::createFromInt($operand);

            $cpu->incrementPC();

            // Dummy read
            $cpu->getMemory($cpu->getPC(), dummy: true);
            $cpu->endTick();

            $old = $cpu->getPC();

            $cpu->addToPC($displacement);

            $new = $cpu->getPC();

            if (($new & 0xFF00) !== ($old & 0xFF00)) {
                // Dummy read
                $cpu->getMemory($old & 0xFF00 | $new & 0x00FF, dummy: true);
                $cpu->endTick();
            }
        }
    }
}
