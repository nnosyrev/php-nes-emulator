<?php

declare(strict_types=1);

namespace App;

use App\Type\Rgb;

final class Frame
{
    public const WIDTH = 256;
    public const HEIGHT = 240;

    private array $data = [];

    public function __construct(Rgb $rgb)
    {
        $this->init($rgb);
    }

    private function init(Rgb $rgb): void
    {
        for ($x = 0; $x < self::WIDTH; $x++) {
            for ($y = 0; $y < self::HEIGHT; $y++) {
                $this->setPixel($x, $y, $rgb);
            }
        }
    }

    public function setPixel(int $x, int $y, Rgb $rgb): void
    {
        $this->data[$x][$y] = $rgb;
    }

    public function getPixel(int $x, int $y): Rgb
    {
        return $this->data[$x][$y];
    }
}
