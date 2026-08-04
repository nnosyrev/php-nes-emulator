<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

final class IndirectMode implements ModeInterface
{
    public function getOperandAddress(CPU $cpu, bool $forceDummyRead = false): int /* UInt16 */
    {
        $addr = $cpu->getMemoryUInt16($cpu->getPC());

        $cpu->endTick();
        $cpu->endTick();

        if (($addr & 0x00FF) === 0x00FF) {
            $low = $cpu->getMemory($addr);
            $high = $cpu->getMemory($addr & 0xFF00);

            $cpu->endTick();

            return ($high << 8) | $low;
        }

        $result = $cpu->getMemoryUInt16($addr);

        $cpu->endTick();

        return $result;
    }
}
