<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt8;

final class IndirectXMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU, bool $forceDummyRead = false): int /* UInt16 */
    {
        $param = $CPU->getMemory($CPU->getPC());

        $CPU->endTick();

        // Dummy read
        $CPU->getMemory($param, dummy: true);
        $CPU->endTick();

        $ptr = UInt8::add($param, $CPU->getRegisterX());

        $low = $CPU->getMemory($ptr);
        $high = $CPU->getMemory(UInt8::increment($ptr));

        $CPU->endTick();
        $CPU->endTick();

        return ($high << 8) | $low;
    }
}
