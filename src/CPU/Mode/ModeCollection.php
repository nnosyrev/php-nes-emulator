<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use DI\Container;
use DirectoryIterator;
use Exception;
use ReflectionClass;

final class ModeCollection
{
    public static function create(Container $container): self
    {
        $modes = [];

        foreach (new DirectoryIterator(__DIR__) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $fileName = str_replace('.php', '', $fileInfo->getFilename());
            $fullClassName = 'App\\CPU\\Mode\\' . $fileName;

            $reflection = new ReflectionClass($fullClassName);

            if ($reflection->isInstantiable() && $reflection->implementsInterface(ModeInterface::class)) {
                $modes[$fullClassName] = $container->get($fullClassName);
            }
        }

        return new self($modes);
    }

    public function __construct(private array $modes) {}

    public function get(string $class): ModeInterface
    {
        if (!\array_key_exists($class, $this->modes)) {
            throw new Exception('Incorrect mode class');
        }

        return $this->modes[$class];
    }
}
