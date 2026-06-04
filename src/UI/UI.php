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
    private \FFI\CData $texture;

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

        $this->texture = $this->sdl->SDL_CreateTexture(
            $this->renderer,
            SDL::SDL_PIXELFORMAT_RGBA32,
            SDL::SDL_TEXTUREACCESS_STREAMING,
            Frame::WIDTH,
            Frame::HEIGHT
        );
    }

    public function render(Frame $frame): void
    {
        $this->sdl->SDL_SetRenderDrawColor($this->renderer, 100, 0, 0, 0);
        $this->sdl->SDL_RenderClear($this->renderer);

        $pixels = $this->sdl->new('int *');
        $pitch = $this->sdl->new('int');

        if (0 == $this->sdl->SDL_LockTexture($this->texture, null, $this->sdl->cast('void**', \FFI::addr($pixels)), \FFI::addr($pitch))) {
            foreach ($frame->getPixels() as $key => &$value) {
                $pixels[$key] = $value;
            }

            $this->sdl->SDL_UnlockTexture($this->texture);
        }

        $this->sdl->SDL_RenderCopy($this->renderer, $this->texture, null, null);

        $this->sdl->SDL_RenderPresent($this->renderer);
    }

    public function __destruct()
    {
        $this->sdl->SDL_DestroyTexture($this->texture);
        $this->sdl->SDL_DestroyRenderer($this->renderer);
        $this->sdl->SDL_DestroyWindow($this->window);
        $this->sdl->SDL_Quit();
    }
}
