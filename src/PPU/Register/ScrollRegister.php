<?php

declare(strict_types=1);

namespace App\PPU\Register;

final class ScrollRegister
{
    private int /* UInt8 */ $scrollX = 0;
    private int /* UInt8 */ $scrollY = 0;

    private bool $latch = true;

    public function set(int /* UInt8 */ $value): void
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
