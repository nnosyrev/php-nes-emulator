<?php

declare(strict_types=1);

namespace App\PPU\Register;

use App\Type\UInt8;

final class ScrollRegister
{
    private UInt8 $scrollX;
    private UInt8 $scrollY;

    private bool $latch = true;

    public function __construct()
    {
        $this->scrollX = new UInt8(0);
        $this->scrollY = new UInt8(0);
    }

    public function set(UInt8 $value): void
    {
        if ($this->latch) {
            $this->scrollX = $value;
        } else {
            $this->scrollY = $value;
        }

        $this->latch = !$this->latch;
    }

    public function resetLatch(): void
    {
        $this->latch = true;
    }
}
