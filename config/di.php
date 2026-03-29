<?php

declare(strict_types=1);

use App\CPU\Instruction\InstructionFactory;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\UI\UI;
use App\UI\UIInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

return [
    UIInterface::class => DI\create(UI::class),
    EventDispatcherInterface::class => DI\create(EventDispatcher::class),
    InstructionFactoryInterface::class => DI\create(InstructionFactory::class),
];
