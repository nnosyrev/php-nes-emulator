<?php

declare(strict_types=1);

namespace App\PPU;

use Exception;

final class Frame
{
    public const WIDTH = 256;
    public const HEIGHT = 240;

    private array $data = [];

    public function __construct(int $color)
    {
        $this->init($color);
    }

    private function init(int $color): void
    {
        for ($i = 0; $i < self::WIDTH * self::HEIGHT; $i++) {
            $this->data[$i] = $color;
        }
    }

    public function setPixel(int $x, int $y, int $color): void
    {
        if ($x > self::WIDTH || $y > self::HEIGHT) {
            return;
            throw new Exception('Something went wrong.');
        }

        $pixelPosition = $y * self::WIDTH + $x;

        $this->data[$pixelPosition] = $color;
    }

    public function getPixels(): array
    {
        return $this->data;
    }
}
