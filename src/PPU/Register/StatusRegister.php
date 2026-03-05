<?php

declare(strict_types=1);

namespace App\PPU\Register;

use App\Type\UInt8;

final class StatusRegister
{
    // 7  bit  0
    // ---- ----
    // VSOx xxxx
    // |||| ||||
    // |||+-++++- (PPU open bus or 2C05 PPU identifier)
    // ||+------- Sprite overflow flag
    // |+-------- Sprite 0 hit flag
    // +--------- Vblank flag, cleared on read. Unreliable; see below.
    private UInt8 $bits;

    public function __construct()
    {
        $this->bits = new UInt8(0b00000000);
    }

    public function get(): UInt8
    {
        $bits = $this->bits;

        $this->bits = $this->bits->and(new UInt8(0b01111111));

        return $bits;
    }
}
