<?php

declare(strict_types=1);

namespace App\Http\Resources\Order;

use App\Http\Resources\Customer\CustomerResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
final class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ref' => $this->ref,
            'status' => $this->status,
            'price_excluding_vat' => $this->price_excluding_vat,
            'price' => $this->price,
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'products' => OrderProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
