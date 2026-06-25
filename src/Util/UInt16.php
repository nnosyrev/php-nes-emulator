<?php

namespace App\Util;

final class UInt16
{
    private const BASE = 65536;

    public static function check(int /* UInt16 */ $value): bool
    {
        return ($value >= 0 && $value <= (self::BASE - 1));
    }

    public static function add(int /* UInt16|UInt8 */ $a, int /* UInt8|Int8|UInt16 */ $b): int /* UInt16 */
    {
        assert(self::check($a) || UInt8::check($a));
        assert(self::check($b) || UInt8::check($b) || Int8::check($b));

        return self::mod($a + $b);
    }

    public static function subtract(int /* $UInt16 */ $value, int /* UInt8 */ $sub): int /* UInt16 */
    {
        return self::mod($value - $sub + self::BASE);
    }

    public static function increment(int /* UInt16 */ $value): int /* UInt16 */
    {
        assert(self::check($value));

        return self::mod($value + 1);
    }

    public static function decrement(int /* UInt16 */ $value): int /* UInt16 */
    {
        assert(self::check($value));

        return self::mod($value - 1 + self::BASE);
    }

    public static function shiftToRight(int /* UInt16 */ $value, int $bits): int /* UInt16 */
    {
        return $value >> $bits;
    }

    public static function inInterval(int /* UInt16 */ $value, int $from, int $to): bool
    {
        return ($from <= $value && $value <= $to);
    }

    public static function hexString(int /* UInt16 */ $value): string
    {
        return '0x' . \strtoupper(\dechex($value));
    }

    private static function mod(int /* UInt16 */ $value): int /* UInt16 */
    {
        return $value % self::BASE;
    }
}
