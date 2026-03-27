<?php

declare(strict_types=1);

namespace App\UI;

use App\Frame;

interface UIInterface
{
    public function render(Frame $frame): void;
}
