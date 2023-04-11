<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Product;
use App\Traits\CalculatePriceTrait;

final class StoreOrUpdateProductAction
{
    use CalculatePriceTrait;

    public function __construct(private readonly ?int $productId = null)
    {
    }

    public function handle(
        string $name,
        string $ref,
        int $vat,
        int $priceExcludingVat,
    ): Product {
        return Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'name' => $name,
                'ref' => $ref,
                'vat' => $vat,
                'price_excluding_vat' => $priceExcludingVat,
                'price' => $this->calculatePrice(priceExcludingVat: $priceExcludingVat, vat: $vat),
                'is_archived' => false,
            ]
        );
    }
}
