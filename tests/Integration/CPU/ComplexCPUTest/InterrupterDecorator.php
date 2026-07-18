<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

use App\CPU\CPU;
use App\CPU\Interrupter\Interrupter;
use App\CPU\Interrupter\InterrupterInterface;
use Fiber;

final class InterrupterDecorator implements InterrupterInterface
{
    public function __construct(private readonly Interrupter $cpuInterrupter) {}

    public function init(CPU $cpu, string $method): void
    {
        $this->cpuInterrupter->init($cpu, $method);
    }

    public function startTick(): ?array
    {
        return $this->cpuInterrupter->startTick();
    }

    public function endTick(): void
    {
        Fiber::suspend(CycleStorage::pop());
    }
}
