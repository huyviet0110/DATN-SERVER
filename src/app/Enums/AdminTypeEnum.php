<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AdminTypeEnum extends Enum
{
    const SUPER_ADMIN  = 0;
    const BUS_OPERATOR = 1;
}
