<?php

declare(strict_types=1);

namespace App\Bus;

interface BusInterface
{
    public function getMemory(int /* UInt16 */ $addr): int /* UInt8 */;

    public function setMemory(int /* UInt16 */ $addr, int /* UInt8 */ $data): void;

    public function setMemoryUInt16(int /* UInt16 */ $addr, int /* UInt16 */ $data): void;

    public function getMemoryUInt16(int /* UInt16 */ $addr): int /* UInt16 */;
}
