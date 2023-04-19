<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected $fillable = [
        'vat',
        'price_excluding_vat',
        'price',
        'quantity'
    ];

    protected $hidden = [
        'order_id',
        'product_id'
    ];
}
