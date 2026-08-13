<?php

namespace App\Util;

final class UInt8 extends AbstractUInt
{
    public const BASE = 256;

    public static function getBase(): int
    {
        return self::BASE;
    }
}
