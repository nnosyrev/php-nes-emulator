<?php

namespace App\Util;

final class UInt16 extends AbstractUInt
{
    private const BASE = 65536;

    public static function getBase(): int
    {
        return self::BASE;
    }

    public static function add(int /* UInt16|UInt8 */ $a, int /* UInt8|Int8|UInt16 */ $b): int /* UInt16 */
    {
        assert(static::check($a) || UInt8::check($a));
        assert(static::check($b) || UInt8::check($b) || Int8::check($b));

        return static::mod($a + $b + self::BASE);
    }

    public static function inInterval(int /* UInt16 */ $value, int $from, int $to): bool
    {
        return ($from <= $value && $value <= $to);
    }

    public static function hexString(int /* UInt16 */ $value): string
    {
        return '0x' . \strtoupper(\dechex($value));
    }
}
