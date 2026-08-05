<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class BRK implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        // TODO: To fix instructions tests.
        // In the PHPTest, PLPTest, RTITest and SEITest, a stub of this class
        // is created to disable the check (because the I flag appears in them)
        //if (!$cpu->getFlagI()) {
            //throw new BreakException('Break');
        //}

        $cpu->getMemory($cpu->getPC(), dummy: true);
        $cpu->endTick();

        $cpu->addToPC(1);

        $cpu->pushToStackUInt16($cpu->getPC());

        $flags = $cpu->getFlagsAsUInt8();
        $flags = UInt8::or($flags, CPU::FLAG_B);

        $cpu->pushToStack($flags);

        $cpu->setFlagI(true);

        $cpu->setPC($cpu->getMemoryUInt16(CPU::IRQ_HANDLER));
    }
}
