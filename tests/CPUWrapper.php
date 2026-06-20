<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\CPU;
use App\CPU\Exception\BreakException;

final class CPUWrapper
{
    public function __construct(private readonly CPU $cpu) {}

    public function run(): void
    {
        try {
            $this->cpu->run();
        } catch (BreakException) {
            return;
        }
    }

    public function __call($name, $arguments)
    {
        return $this->cpu->$name(...$arguments);
    }
}
