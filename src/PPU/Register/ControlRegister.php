<?php

declare(strict_types=1);

namespace App\PPU\Register;

use App\Type\UInt8;

final class ControlRegister
{
    private const VRAM_ADDR_INCREMENT = 0b00000100;

    // 7  bit  0
    // ---- ----
    // VPHB SINN
    // |||| ||||
    // |||| ||++- Base nametable address
    // |||| ||    (0 = $2000; 1 = $2400; 2 = $2800; 3 = $2C00)
    // |||| |+--- VRAM address increment per CPU read/write of PPUDATA
    // |||| |     (0: add 1, going across; 1: add 32, going down)
    // |||| +---- Sprite pattern table address for 8x8 sprites
    // ||||       (0: $0000; 1: $1000; ignored in 8x16 mode)
    // |||+------ Background pattern table address (0: $0000; 1: $1000)
    // ||+------- Sprite size (0: 8x8 pixels; 1: 8x16 pixels)
    // |+-------- PPU master/slave select
    // |          (0: read backdrop from EXT pins; 1: output color on EXT pins)
    // +--------- Generate an NMI at the start of the
    //            vertical blanking interval (0: off; 1: on)
    private UInt8 $bits;

    public function __construct()
    {
        $this->bits = new UInt8(0b00000000);
    }

    public function set(UInt8 $value): void
    {
        $this->bits = $value;
    }

    public function getAddressIncrement(): UInt8
    {
        if ($this->bits->and(new UInt8(self::VRAM_ADDR_INCREMENT))->value === 0) {
            return new UInt8(1);
        }

        return new UInt8(32);
    }
}
