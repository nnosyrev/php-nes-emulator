<?php

declare(strict_types=1);

use App\Bus\Bus;
use App\Bus\BusInterface;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\UI\UI;
use App\UI\UIInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

return [
    UIInterface::class => DI\get(UI::class),
    BusInterface::class => DI\get(Bus::class),
    EventDispatcherInterface::class => DI\get(EventDispatcher::class),
    InstructionFactoryInterface::class => DI\get(InstructionFactory::class),
];
