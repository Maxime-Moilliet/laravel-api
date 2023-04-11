<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Product;

final class StoreOrUpdateProductAction
{
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
                'price' => intval($priceExcludingVat + ($priceExcludingVat * ($vat / 100))),
                'is_archived' => false,
            ]
        );
    }
}
