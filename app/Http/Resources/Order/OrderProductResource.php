<?php

declare(strict_types=1);

namespace App\Http\Resources\Order;

use App\Http\Resources\Product\ProductResource;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 * @property OrderProduct $pivot
 */
final class OrderProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quantity' => $this->pivot->quantity,
            'vat' => $this->pivot->vat,
            'price_excluding_vat' => $this->pivot->price_excluding_vat,
            'price' => $this->pivot->price,
            'product' => ProductResource::make($this->resource),
        ];
    }
}
