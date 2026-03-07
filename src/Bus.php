<?php

declare(strict_types=1);

namespace App;

use App\Type\UInt16;
use App\Type\UInt8;

final class Bus
{
    private array $memory = [];

    public function setMemory(UInt16 $addr, UInt8 $data): void
    {
        $this->memory[$addr->value] = $data->value;
    }

    public function getMemory(UInt16 $addr): UInt8
    {
        return new UInt8($this->memory[$addr->value]);
    }

    public function setMemoryUInt16(UInt16 $addr, UInt16 $data): void
    {
        $high = $data->value >> 8;
        $low = $data->value & 0xFF;

        $this->memory[$addr->value] = $low;
        $this->memory[$addr->value + 1] = $high;
    }

    public function getMemoryUInt16(UInt16 $addr): UInt16
    {
        $low = $this->memory[$addr->value];
        $high = $this->memory[$addr->increment()->value];

        $res = ($high << 8) | $low;

        return new UInt16($res);
    }
}
