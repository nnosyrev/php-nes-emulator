<?php

namespace App\Util;

final class Debug
{
    private static int $timestamp;
    private static int $calls;

    public static function dumpCallsPerSecond(): void
    {
        if (empty(self::$timestamp)) {
            self::reset();
        } elseif (self::$timestamp < time()) {
            var_dump(self::$calls);
            self::reset();
        } else {
            self::$calls += 1;
        }
    }

    private static function reset(): void
    {
        self::$timestamp = time();
        self::$calls = 0;
    }
}
