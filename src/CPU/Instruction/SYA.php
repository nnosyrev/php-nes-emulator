<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt16;
use App\Util\UInt8;

final class SYA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        //$high = $addr->shiftToRight(8)->toUInt8();
        $high = UInt16::shiftToRight($addr, 8);

        //$result = $cpu->getRegisterY()->and($high->increment());
        $result = UInt8::and($cpu->getRegisterY(), UInt8::increment($high));

        $cpu->setMemory($addr, $result);
    }
}
