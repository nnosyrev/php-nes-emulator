<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\CPU;
use App\Rom\RomInterface;
use App\Type\UInt8;
use DI\Container;

trait CPUTestTrait
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    protected function loadProgramToRom(array $program): void
    {
        $rom = $this->createStub(RomInterface::class);
        $rom->method('getPrgRom')
            ->willReturn($program);

        $this->container->set(RomInterface::class, $rom);
    }

    protected function getCpu(): CPU
    {
        return $this->container->get(CPU::class);
    }

    protected function getFlagNValue(UInt8 $byte): bool
    {
        return ($byte->value & 0b10000000) === 0b10000000;
    }
}
