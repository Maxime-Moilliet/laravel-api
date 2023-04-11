<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Product;

final class StoreProductAction
{
    public function handle(
        string $name,
        string $ref,
        int $vat,
        int $priceExcludingVat,
    ): Product {
        return Product::create([
            'name' => $name,
            'ref' => $ref,
            'vat' => $vat,
            'price_excluding_vat' => $priceExcludingVat,
            'price' => intval($priceExcludingVat + ($priceExcludingVat * ($vat / 100))),
            'is_archived' => false,
        ]);
    }
}
