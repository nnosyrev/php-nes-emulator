<?php

declare(strict_types=1);

namespace App\UI;

use App\Frame;

final class UI
{
    private const SQUARE_WIDTH = 3;
    private const WINDOW_WIDTH = Frame::WIDTH * self::SQUARE_WIDTH;
    private const WINDOW_HEIGHT = Frame::HEIGHT * self::SQUARE_WIDTH;

    private \SDL_Window $window;
    private mixed $renderer;

    public function __construct()
    {
        // Init
        SDL_Init(SDL_INIT_VIDEO);

        $this->window = SDL_CreateWindow(
            "NES emulator",
            SDL_WINDOWPOS_UNDEFINED,
            SDL_WINDOWPOS_UNDEFINED,
            self::WINDOW_WIDTH,
            self::WINDOW_HEIGHT,
            SDL_WINDOW_SHOWN
        );

        $this->renderer = SDL_CreateRenderer($this->window, 0, SDL_RENDERER_ACCELERATED);
    }

    public function render(Frame $frame): void
    {
        // Clear screen
        SDL_SetRenderDrawColor($this->renderer, 100, 0, 0, 0);
        SDL_RenderClear($this->renderer);

        // Show frame
        for ($x = 0; $x < Frame::WIDTH; $x++) {
            for ($y = 0; $y < Frame::HEIGHT; $y++) {
                $rgb = $frame->getPixel($x, $y);

                SDL_SetRenderDrawColor($this->renderer, $rgb->r->value, $rgb->g->value, $rgb->b->value, 255);

                $rect = new \SDL_Rect($x * self::SQUARE_WIDTH, $y * self::SQUARE_WIDTH, self::SQUARE_WIDTH, self::SQUARE_WIDTH);
                SDL_RenderFillRect($this->renderer, $rect);
            }
        }
        SDL_RenderPresent($this->renderer);
    }

    public function __destruct()
    {
        SDL_DestroyRenderer($this->renderer);
        SDL_DestroyWindow($this->window);
        SDL_Quit();
    }
}
