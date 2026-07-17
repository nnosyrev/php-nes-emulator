<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

final class CycleStorage
{
    public const string TYPE_READ = 'read';
    public const string TYPE_WRITE = 'write';

    private static array $queue = [];

    public static function reset(): void
    {
        self::$queue = [];
    }

    public static function push(int /* UInt16 */ $addr, int /* UInt8 */ $value, string $type): void
    {
        self::$queue[] = [$addr, $value, $type];
    }

    public static function pop(): ?array
    {
        if (empty(self::$queue)) {
            return null;
        }

        $first = \array_first(self::$queue);

        self::$queue = \array_slice(self::$queue, 1);

        return $first;
    }

}
