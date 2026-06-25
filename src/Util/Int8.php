<?php

declare(strict_types=1);

namespace App\Util;

final class Int8
{
    public static function check(int $value): bool
    {
        return (-128 <= $value && $value <= 127);
    }

    public static function createFromInt(int $int): int
    {
        return ($int >= 0x80) ? -(($int ^ 0xFF) + 1) : $int;
    }
}
