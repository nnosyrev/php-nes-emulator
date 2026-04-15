<?php

declare(strict_types=1);

namespace App\UI;

use App\PPU\Frame;
use Serafim\SDL\SDL;

final class UI implements UIInterface
{
    private const SQUARE_WIDTH = 3;
    private const WINDOW_WIDTH = Frame::WIDTH * self::SQUARE_WIDTH;
    private const WINDOW_HEIGHT = Frame::HEIGHT * self::SQUARE_WIDTH;

    private SDL $sdl;
    private \FFI\CData $window;
    private \FFI\CData $renderer;

    public function __construct()
    {
        // Init
        $this->sdl = new SDL();
        $this->sdl->SDL_Init(SDL::SDL_INIT_VIDEO);

        $this->window = $this->sdl->SDL_CreateWindow(
            'NES emulator',
            SDL::SDL_WINDOWPOS_UNDEFINED,
            SDL::SDL_WINDOWPOS_UNDEFINED,
            self::WINDOW_WIDTH,
            self::WINDOW_HEIGHT,
            SDL::SDL_WINDOW_SHOWN
        );

        $this->renderer = $this->sdl->SDL_CreateRenderer($this->window, 0, SDL::SDL_RENDERER_ACCELERATED);
    }

    public function render(Frame $frame): void
    {
        // Clear screen
        $this->sdl->SDL_SetRenderDrawColor($this->renderer, 100, 0, 0, 0);
        $this->sdl->SDL_RenderClear($this->renderer);

        // Show frame
        for ($x = 0; $x < Frame::WIDTH; $x++) {
            for ($y = 0; $y < Frame::HEIGHT; $y++) {
                $rgb = $frame->getPixel($x, $y);

                $this->sdl->SDL_SetRenderDrawColor($this->renderer, $rgb->r->value, $rgb->g->value, $rgb->b->value, 255);

                $rect = $this->sdl->new('SDL_Rect');
                $rect->x = $x * self::SQUARE_WIDTH;
                $rect->y = $y * self::SQUARE_WIDTH;
                $rect->w = self::SQUARE_WIDTH;
                $rect->h = self::SQUARE_WIDTH;

                $this->sdl->SDL_RenderFillRect($this->renderer, \FFI::addr($rect));
            }
        }

        $this->sdl->SDL_RenderPresent($this->renderer);
    }

    public function __destruct()
    {
        $this->sdl->SDL_DestroyRenderer($this->renderer);
        $this->sdl->SDL_DestroyWindow($this->window);
        $this->sdl->SDL_Quit();
    }
}
