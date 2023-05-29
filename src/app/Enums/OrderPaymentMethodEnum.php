<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderPaymentMethodEnum extends Enum
{
    const COD                = 0;
    const ATM                = 1;
    const INTERNATIONAL_CARD = 2;
    const MOMO               = 3;
}
