<?php

declare(strict_types=1);

namespace App\UI;

use App\Joystick;
use App\PPU\Frame;

interface UIInterface
{
    public function render(Frame $frame): void;

    public function processEvent(Joystick $joystick): void;
}
