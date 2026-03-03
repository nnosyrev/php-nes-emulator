<?php

declare(strict_types=1);

namespace App\Type;

final class Rgb
{
    public function __construct(
        public readonly UInt8 $r,
        public readonly UInt8 $g,
        public readonly UInt8 $b,
    ) {}
}
