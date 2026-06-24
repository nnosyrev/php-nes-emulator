<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class DEX implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $byte = $CPU->getRegisterX();

        $CPU->setRegisterX(UInt8::decrement($byte));
    }
}
