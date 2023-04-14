<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    case INCOMPLETE = 'incomplete';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
}
