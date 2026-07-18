<?php

declare(strict_types=1);

namespace App\CPU\Interrupter;

use App\CPU\CPU;

interface InterrupterInterface
{
    public function init(CPU $cpu, string $method): void;

    public function startTick(): ?array;

    public function endTick(): void;
}
