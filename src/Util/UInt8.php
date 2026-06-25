<?php

namespace App\Util;

final class UInt8
{
    public const BASE = 256;

    public static function check(int /* UInt8 */ $uint8): bool
    {
        return ($uint8 >= 0 && $uint8 <= (self::BASE - 1));
    }

    public static function and(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        return $a & $b;
    }

    public static function or(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        return $a | $b;
    }

    public static function add(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        return self::mod($a + $b);
    }

    public  static function subtract(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        return self::mod($a - $b + self::BASE);
    }

    public static function increment(int /* UInt8 */ $value): int /* UInt8 */
    {
        assert(self::check($value));

        return self::mod($value + 1);
    }

    public static function decrement(int /* UInt8 */ $value): int /* UInt8 */
    {
        assert(self::check($value));

        return self::mod($value - 1 + self::BASE);
    }

    public static function shiftToLeft(int /* UInt8 */ $value, int $bits): int /* UInt8 */
    {
        assert(self::check($value));

        return self::mod($value << $bits);
    }

    public static function shiftToRight(int /* UInt8 */ $value, int $bits): int /* UInt8 */
    {
        assert(self::check($value));

        return $value >> $bits;
    }

    public static function xor(int /* UInt8 */ $value, int /* UInt8 */ $xor): int /* UInt8 */
    {
        assert(self::check($value));
        assert(self::check($xor));

        return $value ^ $xor;
    }

    private static function mod(int /* UInt8 */ $value): int /* UInt8 */
    {
        return $value % self::BASE;
    }
}
