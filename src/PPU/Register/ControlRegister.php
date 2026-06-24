<?php

declare(strict_types=1);

namespace App\PPU\Register;

final class ControlRegister
{
    private const VRAM_ADDR_INCREMENT      = 0b00000100;
    private const SPRITE_PATTERN_TABLE     = 0b00001000;
    private const BACKGROUND_PATTERN_TABLE = 0b00010000;
    private const NMI_ENABLE               = 0b10000000;

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
    // +--------- Vblank NMI enable (0: off, 1: on)
    private int /* UInt8 */ $bits = 0b00000000;

    public function set(int /* UInt8 */ $value): void
    {
        $this->bits = $value;
    }

    public function getAddressIncrement(): int /* UInt8 */
    {
        if (($this->bits & self::VRAM_ADDR_INCREMENT) === 0) {
            return 1;
        }

        return 32;
    }

    public function getNMIEnableBit(): bool
    {
        return (($this->bits & self::NMI_ENABLE) === self::NMI_ENABLE);
    }

    public function getSpritePatternTableBit(): bool
    {
        return ($this->bits & self::SPRITE_PATTERN_TABLE) !== 0;
    }

    public function getBackgroundPatternTableBit(): bool
    {
        return ($this->bits & self::BACKGROUND_PATTERN_TABLE) !== 0;
    }
}
