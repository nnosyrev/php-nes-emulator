<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt16;

final class AbsoluteXMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $param = $CPU->getMemoryUInt16($CPU->getPC());

        $CPU->endTick();
        $CPU->endTick();

        $result = UInt16::add($param, $CPU->getRegisterX());

        if (($result & 0xFF00) !== ($param & 0xFF00)) {
            $CPU->getMemory($param & 0xFF00 | $result & 0x00FF); // Dummy read
            $CPU->endTick();
        }

        return $result;
    }
}
