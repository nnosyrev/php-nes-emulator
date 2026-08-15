<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use DI\Container;
use DirectoryIterator;
use Exception;
use ReflectionClass;

final class InstructionCollection implements InstructionCollectionInterface
{
    public static function create(Container $container): self
    {
        $instructions = [];

        foreach (new DirectoryIterator(__DIR__) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $fileName = str_replace('.php', '', $fileInfo->getFilename());
            $fullClassName = 'App\\CPU\\Instruction\\' . $fileName;

            $reflection = new ReflectionClass($fullClassName);

            if ($reflection->isInstantiable() && $reflection->implementsInterface(InstructionInterface::class)) {
                $instructions[$fullClassName] = $container->get($fullClassName);
            }
        }

        return new self($instructions);
    }

    public function __construct(private array $instructions) {}

    public function get(string $class): InstructionInterface
    {
        if (!\array_key_exists($class, $this->instructions)) {
            throw new Exception('Incorrect instruction class');
        }

        return $this->instructions[$class];
    }
}
