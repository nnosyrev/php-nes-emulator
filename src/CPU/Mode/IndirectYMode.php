<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt16;
use App\Util\UInt8;

final class IndirectYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $param = $CPU->getMemory($CPU->getPC());

        $CPU->endTick();

        $low = $CPU->getMemory($param);
        $high = $CPU->getMemory(UInt8::increment($param));

        $CPU->endTick();
        $CPU->endTick();

        $result = ($high << 8) | $low;

        $addr = UInt16::add($result, $CPU->getRegisterY());

        if (($addr & 0xFF00) !== ($result & 0xFF00)) {
            $CPU->getMemory($result & 0xFF00 | $addr & 0x00FF); // Dummy read
            $CPU->endTick();
        }

        return $addr;
    }
}
