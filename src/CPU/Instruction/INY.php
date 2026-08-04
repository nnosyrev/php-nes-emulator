<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class INY implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->getMemory($cpu->getPC(), dummy: true);

        $byte = $cpu->getRegisterY();

        $cpu->setRegisterY(UInt8::increment($byte));
    }
}
