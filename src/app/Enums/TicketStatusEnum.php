<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TicketStatusEnum extends Enum
{
    const NOT_CANCELED = 0;
    const CANCELED     = 1;
}
