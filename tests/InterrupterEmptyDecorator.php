<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\CPU;
use App\CPU\Interrupter\Interrupter;
use App\CPU\Interrupter\InterrupterInterface;

final class InterrupterEmptyDecorator implements InterrupterInterface
{
    public function __construct(private readonly Interrupter $cpuInterrupter) {}

    public function init(CPU $cpu, string $method): void {}

    public function startTick(): ?array
    {
        return null;
    }

    public function endTick(): void {}
}
