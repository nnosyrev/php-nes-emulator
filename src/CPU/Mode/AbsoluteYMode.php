<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt16;

final class AbsoluteYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU, bool $forceDummyRead = false): int /* UInt16 */
    {
        $param = $CPU->getMemoryUInt16($CPU->getPC());

        $CPU->endTick();
        $CPU->endTick();

        $result = UInt16::add($param, $CPU->getRegisterY());

        if ($forceDummyRead || ($result & 0xFF00) !== ($param & 0xFF00)) {
            // Dummy read
            $CPU->getMemory($param & 0xFF00 | $result & 0x00FF, dummy: true);
            $CPU->endTick();
        }

        return $result;
    }
}
