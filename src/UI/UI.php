<?php

declare(strict_types=1);

namespace App\UI;

use App\CPU\Exception\BreakException;
use App\Joystick;
use App\PPU\Frame;
use Serafim\SDL\Event\Type;
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
    private \FFI\CData $event;
    private \FFI\CData $pixels;
    private \FFI\CData $pitch;

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

        $this->event = $this->sdl->new('SDL_Event');
        $this->pixels = $this->sdl->new('int *');
        $this->pitch = $this->sdl->new('int');
    }

    public function render(Frame $frame): void
    {
        $this->sdl->SDL_SetRenderDrawColor($this->renderer, 100, 0, 0, 0);
        $this->sdl->SDL_RenderClear($this->renderer);

        if (0 == $this->sdl->SDL_LockTexture($this->texture, null, $this->sdl->cast('void**', \FFI::addr($this->pixels)), \FFI::addr($this->pitch))) {
            foreach ($frame->getPixels() as $key => $value) {
                $this->pixels[$key] = $value;
            }

            $this->sdl->SDL_UnlockTexture($this->texture);
        }

        $this->sdl->SDL_RenderCopy($this->renderer, $this->texture, null, null);

        $this->sdl->SDL_RenderPresent($this->renderer);
    }

    public function processEvent(Joystick $joystick): void
    {
        $this->sdl->SDL_PollEvent(\FFI::addr($this->event));

        switch ($this->event->type) {
            case Type::SDL_QUIT:
                throw new BreakException('Quit');
            case Type::SDL_KEYDOWN:
                $symChar = $this->getSymChar();

                if ($symChar == 'w') {
                    $joystick->setButtonBit(Joystick::BUTTON_UP, true);
                } elseif ($symChar == 's') {
                    $joystick->setButtonBit(Joystick::BUTTON_DOWN, true);
                } elseif ($symChar == 'a') {
                    $joystick->setButtonBit(Joystick::BUTTON_LEFT, true);
                } elseif ($symChar == 'd') {
                    $joystick->setButtonBit(Joystick::BUTTON_RIGHT, true);
                } elseif ($symChar == 'n') {
                    $joystick->setButtonBit(Joystick::BUTTON_SELECT, true);
                } elseif ($symChar == 'm') {
                    $joystick->setButtonBit(Joystick::BUTTON_START, true);
                } elseif ($symChar == 'o') {
                    $joystick->setButtonBit(Joystick::BUTTON_B, true);
                } elseif ($symChar == 'p') {
                    $joystick->setButtonBit(Joystick::BUTTON_A, true);
                } elseif ($symChar == 'q') {
                    throw new BreakException('Quit');
                }
                break;
            case Type::SDL_KEYUP:
                $symChar = $this->getSymChar();

                if ($symChar == 'w') {
                    $joystick->setButtonBit(Joystick::BUTTON_UP, false);
                } elseif ($symChar == 's') {
                    $joystick->setButtonBit(Joystick::BUTTON_DOWN, false);
                } elseif ($symChar == 'a') {
                    $joystick->setButtonBit(Joystick::BUTTON_LEFT, false);
                } elseif ($symChar == 'd') {
                    $joystick->setButtonBit(Joystick::BUTTON_RIGHT, false);
                } elseif ($symChar == 'n') {
                    $joystick->setButtonBit(Joystick::BUTTON_SELECT, false);
                } elseif ($symChar == 'm') {
                    $joystick->setButtonBit(Joystick::BUTTON_START, false);
                } elseif ($symChar == 'o') {
                    $joystick->setButtonBit(Joystick::BUTTON_B, false);
                } elseif ($symChar == 'p') {
                    $joystick->setButtonBit(Joystick::BUTTON_A, false);
                }
        }
    }

    public function __destruct()
    {
        $this->sdl->SDL_DestroyTexture($this->texture);
        $this->sdl->SDL_DestroyRenderer($this->renderer);
        $this->sdl->SDL_DestroyWindow($this->window);
        $this->sdl->SDL_Quit();
    }

    private function getSymChar(): string
    {
        return chr($this->event->key->keysym->sym % 256);
    }
}
