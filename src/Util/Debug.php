<?php

namespace App\Util;

final class Debug
{
    private static array $timestamp;
    private static array $calls;

    public static function dumpCallsPerSecond(?string $name = null): void
    {
        $name = is_null($name) ? 'Default' : ucfirst($name);

        if (empty(self::$timestamp[$name])) {
            self::reset($name);
        } elseif (self::$timestamp[$name] < time()) {
            echo $name . ': ' . self::$calls[$name] . PHP_EOL;
            self::reset($name);
        } else {
            self::$calls[$name] += 1;
        }
    }

    private static function reset(string $name): void
    {
        self::$timestamp[$name] = time();
        self::$calls[$name] = 0;
    }
}
