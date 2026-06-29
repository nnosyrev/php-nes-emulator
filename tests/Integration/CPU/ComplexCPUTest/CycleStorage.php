<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

final class CycleStorage
{
    private static array $queue = [];

    public static function push(int /* UInt16 */ $addr, int /* UInt8 */ $value, string $type): void
    {
        self::$queue[] = [$addr, $value, $type];
    }

    public static function pop(): array
    {
        //return array_first
    }
}
