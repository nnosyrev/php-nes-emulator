<?php

declare(strict_types=1);

namespace App\Rom;

use App\Mirroring;
use App\Type\UInt8;

interface RomInterface
{
    public function getPrgRom(): array;

    public function getChrRom(): array;

    public function getMapper(): UInt8;

    public function getMirroring(): Mirroring;
}
