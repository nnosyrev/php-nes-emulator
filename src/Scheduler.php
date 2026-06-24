<?php

declare(strict_types=1);

namespace App;

use App\CPU\CPU;
use App\CPU\Exception\BreakException;
use App\PPU\PPU;
use App\PPU\Renderer;
use App\UI\UIInterface;
use App\Util\Debug;

final class Scheduler
{
    public function __construct(
        private readonly CPU $cpu,
        private readonly PPU $ppu,
        private readonly UIInterface $ui,
        private readonly Renderer $renderer,
        private readonly Joystick $joystick,
    ) {}

    public function run(): void
    {
        while (true) {
            try {
                $this->cpu->tick();

                // 1 CPU cycle = 3 PPU cycles
                $this->ppu->tick();
                $this->ppu->tick();
                $this->ppu->tick();

                if ($this->ppu->getNeedRender()) {
                    Debug::dumpCallsPerSecond();

                    $frame = $this->renderer->render();

                    $this->ui->render($frame);
                    $this->ui->processEvent($this->joystick);

                    $this->ppu->setNeedRenderToFalse();
                }
                
                // APU...
            } catch (BreakException) {
                return;
            }
        }
    }
}
