<?php

declare(strict_types=1);

namespace App;

final class Joystick
{
    private bool $strobe = false;
    private int $index = 0;
    private int $bits = 0;

    public const int BUTTON_RIGHT  = 0b10000000;
    public const int BUTTON_LEFT   = 0b01000000;
    public const int BUTTON_DOWN   = 0b00100000;
    public const int BUTTON_UP     = 0b00010000;
    public const int BUTTON_START  = 0b00001000;
    public const int BUTTON_SELECT = 0b00000100;
    public const int BUTTON_B      = 0b00000010;
    public const int BUTTON_A      = 0b00000001;

    public function set(int /* UInt8 */ $data): void
    {
        $this->strobe = ($data & 1) === 1;

        if ($this->strobe) {
            $this->index = 0;
        }
    }

    public function get(): int /* UInt8 */
    {
        if ($this->index > 7) {
            return 1;
        }

        $result = $this->bits & (1 << $this->index) >> $this->index;

        if (!$this->strobe && $this->index <= 7) {
            $this->index += 1;
        }

        return $result;
    }

    public function setButtonBit(int $key, bool $pressed): void
    {
        $this->bits = $pressed ? $this->bits | $key : $this->bits & ~ $key;
    }
}
