<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatusEnum extends Enum
{
    const NEW            = 0;
    const ALREADY_PAID   = 1;
    const CANCELED       = 2;
    const PAYMENT_FAILED = 3;
}
