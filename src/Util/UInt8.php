<?php

namespace App\Util;

use Exception;

final class UInt8
{
    public const BASE = 256;

    public static function check(int /* UInt8 */ $uint8): bool
    {
        return ($uint8 >= 0 && $uint8 <= (self::BASE - 1));
    }

    public static function validate(int /* UInt8 */ $value): void
    {
        if (!self::check($value)) {
            throw new Exception('Invalid value');
        }
    }

    public static function and(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        $result = $a & $b;

        assert(self::check($result));

        return $result;
    }

    public static function or(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        $result = $a | $b;

        assert(self::check($result));

        return $result;
    }

    public static function add(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        $result = self::mod($a + $b);

        assert(self::check($result));

        return $result;
    }

    public  static function subtract(int /* UInt8 */ $a, int /* UInt8 */ $b): int /* UInt8 */
    {
        assert(self::check($a));
        assert(self::check($b));

        $result = self::mod($a - $b + self::BASE);

        assert(self::check($result));

        return $result;
    }

    public static function increment(int /* UInt8 */ $value): int /* UInt8 */
    {
        assert(self::check($value));

        $result = self::mod($value + 1);

        assert(self::check($result));

        return $result;
    }

    public static function decrement(int /* UInt8 */ $value): int /* UInt8 */
    {
        assert(self::check($value));

        $result = self::mod($value - 1 + self::BASE);

        assert(self::check($result));

        return $result;
    }

    public static function shiftToLeft(int /* UInt8 */ $value, int $bits): int /* UInt8 */
    {
        assert(self::check($value));

        $result = self::mod($value << $bits);

        assert(self::check($result));

        return $result;
    }

    public static function shiftToRight(int /* UInt8 */ $value, int $bits): int /* UInt8 */
    {
        assert(self::check($value));

        $result = $value >> $bits;

        assert(self::check($result));

        return $result;
    }

    public static function xor(int /* UInt8 */ $value, int /* UInt8 */ $xor): int /* UInt8 */
    {
        assert(self::check($value));
        assert(self::check($xor));

        $result = $value ^ $xor;

        assert(self::check($result));

        return $result;
    }

    private static function mod(int /* UInt8 */ $value): int /* UInt8 */
    {
        return $value % self::BASE;
    }
}
