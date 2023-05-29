<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BusTypeEnum extends Enum
{
    const SEAT      = 0;
    const BUNK      = 1;
    const LIMOUSINE = 2;
}
