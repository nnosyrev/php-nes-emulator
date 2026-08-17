<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Interrupter\InterrupterInterface;
use App\Rom\RomInterface;
use App\UI\UIInterface;
use DI\Container;
use DI\ContainerBuilder;

trait CPUTestTrait
{
    private Container $container;

    protected function setUp(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../config/di.php');

        $this->container = $builder->build();
        $this->container->set(UIInterface::class, $this->createStub(UIInterface::class));
        $this->container->set(InterrupterInterface::class, $this->container->get(InterrupterEmptyDecorator::class));
        $this->container->set(InstructionFactoryInterface::class, $this->container->get(InstructionFactoryDecorator::class));
    }

    protected function loadProgramToRom(array $program): void
    {
        $rom = $this->createStub(RomInterface::class);
        $rom->method('getPrgRom')
            ->willReturn($program);

        $this->container->set(RomInterface::class, $rom);
    }

    protected function getCpu(): CPUWrapper
    {
        return $this->container->get(CPUWrapper::class);
    }

    protected function getFlagNValue(int /* UInt8 */ $byte): bool
    {
        return ($byte & 0b10000000) === 0b10000000;
    }
}
