<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt8;

final class ZeroPageXMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU, bool $forceDummyRead = false): int /* UInt16 */
    {
        $data = $CPU->getMemory($CPU->getPC()); 
        $CPU->endTick();

        // Dummy read
        $CPU->getMemory($data, dummy: true);
        $CPU->endTick();

        return UInt8::add($data, $CPU->getRegisterX());
    }
}
