<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'ref',
        'vat',
        'price_excluding_vat',
        'price',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];
}
