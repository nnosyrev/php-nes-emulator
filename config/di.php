<?php

declare(strict_types=1);

use App\Bus\Bus;
use App\Bus\BusInterface;
use App\CPU\Instruction\InstructionCollection;
use App\CPU\Instruction\InstructionCollectionInterface;
use App\CPU\Interrupter\Interrupter;
use App\CPU\Interrupter\InterrupterInterface;
use App\UI\UI;
use App\UI\UIInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

return [
    InstructionCollection::class => DI\factory([InstructionCollection::class, 'create']),

    UIInterface::class => DI\get(UI::class),
    BusInterface::class => DI\get(Bus::class),
    EventDispatcherInterface::class => DI\get(EventDispatcher::class),
    InstructionCollectionInterface::class => DI\get(InstructionCollection::class),
    InterrupterInterface::class => DI\get(Interrupter::class),
];
