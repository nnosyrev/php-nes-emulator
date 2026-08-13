<?php

namespace App\Util;

abstract class AbstractUInt
{
    public static function getBase(): int
    {
        throw new \Exception('You need to redefine the method in the child class.');
    }

    public static function check(int /* UInt8 */ $value): bool
    {
        return ($value >= 0 && $value <= (static::getBase() - 1));
    }

    public static function and(int $a, int $b): int
    {
        assert(self::check($a));
        assert(self::check($b));

        return $a & $b;
    }

    public static function or(int $a, int $b): int
    {
        assert(self::check($a));
        assert(self::check($b));

        return $a | $b;
    }

    public static function add(int $a, int $b): int
    {
        assert(self::check($a));
        assert(self::check($b));

        return self::mod($a + $b);
    }

    public static function subtract(int $a, int $b): int
    {
        assert(self::check($a));
        assert(self::check($b));

        return self::mod($a - $b + static::getBase());
    }

    public static function increment(int $value): int
    {
        assert(self::check($value));

        return self::mod($value + 1);
    }

    public static function decrement(int $value): int
    {
        assert(self::check($value));

        return self::mod($value - 1 + static::getBase());
    }

    public static function shiftToLeft(int $value, int $bits): int
    {
        assert(self::check($value));

        return self::mod($value << $bits);
    }

    public static function shiftToRight(int $value, int $bits): int
    {
        assert(self::check($value));

        return $value >> $bits;
    }

    public static function xor(int $value, int $xor): int
    {
        assert(self::check($value));
        assert(self::check($xor));

        return $value ^ $xor;
    }

    public static function not(int $value): int
    {
        assert(self::check($value));

        return ~ $value & static::getBase() - 1;
    }

    protected static function mod(int $value): int
    {
        return $value % static::getBase();
    }
}
