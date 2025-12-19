<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use Exception;

final class ModeFactory
{
    private array $modes = [];

    public function make(string $class): ModeInterface
    {
        if (!array_key_exists($class, $this->modes)) {
            $mode = new $class();

            if (!($mode instanceof ModeInterface)) {
                throw new Exception('Incorrect mode class');
            }

            $this->modes[$class] = $mode;
        }

        return $this->modes[$class];
    }
}
