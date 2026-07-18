<?php

declare(strict_types=1);

namespace App\CPU\Interrupter;

use App\CPU\CPU;
use Exception;
use Fiber;

final class Interrupter implements InterrupterInterface
{
    private Fiber $fiber;

    public function init(CPU $cpu, string $method): void
    {
        $this->fiber = new Fiber([$cpu, $method]);
    }

    public function startTick(): ?array
    {
        if (!$this->fiber->isStarted()) {
            return $this->fiber->start();
        } elseif ($this->fiber->isSuspended()) {
            return $this->fiber->resume();
        }

        throw new Exception('Something went wrong.');
    }

    public function endTick(): void
    {
        // In instructions tests, the run method is simply run without running the tick() method
        if ($this->fiber->isRunning()) {
            Fiber::suspend();
        }
    }
}
