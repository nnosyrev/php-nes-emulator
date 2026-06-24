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
        assert(self::check($a) || ($a >= 0 && $a <= 255));
        // TODO: add an Int8 check
        assert(self::check($b) || ($b >= -128 && $b <= 127));

        $result = self::mod($a + $b);

        assert(self::check($result));

        return $result;
    }

    public static function subtract(int /* $UInt16 */ $value, int /* UInt8 */ $sub): int /* UInt16 */
    {
        $newValue = self::mod($value - $sub + self::BASE);

        return $newValue;
    }

    public static function increment(int /* UInt16 */ $value): int /* UInt16 */
    {
        assert(self::check($value));

        $result = self::mod($value + 1);

        assert(self::check($result));

        return $result;
    }

    public static function decrement(int /* UInt16 */ $value): int /* UInt16 */
    {
        assert(self::check($value));

        $result = self::mod($value - 1 + self::BASE);

        assert(self::check($result));

        return $result;
    }

    public static function shiftToRight(int /* UInt16 */ $value, int $bits): int /* UInt16 */
    {
        $newValue = $value >> $bits;

        return $newValue;
    }

    public static function isInInterval(int /* UInt16 */ $value, int $from, int $to): bool
    {
        return ($from <= $value && $value <= $to);
    }

    public static function isIn(int $value, int ...$args): bool
    {
        return \in_array($value, [...$args]);
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
