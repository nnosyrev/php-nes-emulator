<?php

declare(strict_types=1);

namespace App\PPU\Register;

final class MaskRegister
{
    /*
     * PPUMASK - Rendering settings ($2001 write)
     *
     * 7  bit  0
     * ---- ----
     * BGRs bMmG
     * |||| ||||
     * |||| |||+- Greyscale (0: normal color, 1: greyscale)
     * |||| ||+-- 1: Show background in leftmost 8 pixels of screen, 0: Hide
     * |||| |+--- 1: Show sprites in leftmost 8 pixels of screen, 0: Hide
     * |||| +---- 1: Enable background rendering
     * |||+------ 1: Enable sprite rendering
     * ||+------- Emphasize red (green on PAL/Dendy)
     * |+-------- Emphasize green (red on PAL/Dendy)
     * +--------- Emphasize blue
     */
    private int /* UInt8 */ $mask;

    public function set(int /* UInt8 */ $value): void
    {
        $this->mask = $value;
    }
}
