<?php

declare(strict_types=1);

namespace App\Rom;

use App\Mirroring;

interface RomInterface
{
    public function getPrgRom(): array;

    public function getChrRom(): array;

    public function getMapper(): int /* UInt8 */;

    public function getMirroring(): Mirroring;
}
