<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class BEQ implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        if ($cpu->getFlagZ()) {
            $displacement = $cpu->readMemory($cpu->getPC())->toInt8();

            $cpu
                ->incrementPC()
                ->addToPC($displacement);
        }
    }
}
