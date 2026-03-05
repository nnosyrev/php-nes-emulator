<?php

declare(strict_types=1);

namespace App\PPU;

use App\Type\UInt8;

final class ControlRegister
{
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

    private const NAMETABLE1             = 0b00000001;
    private const NAMETABLE2             = 0b00000010;
    private const VRAM_ADDR_INCREMENT    = 0b00000100;
    private const SPRITE_PATTERN_ADDR    = 0b00001000;
    private const BACKROUND_PATTERN_ADDR = 0b00010000;
    private const SPRITE_SIZE            = 0b00100000;
    private const MASTER_SLAVE_SELECT    = 0b01000000;
    private const GENERATE_NMI           = 0b10000000;

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
