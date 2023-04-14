<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Traits\CalculatePriceTrait;

final class UpdateProductController extends Controller
{
    use CalculatePriceTrait;

    public function __invoke(ProductRequest $request, Product $product): ProductResource
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->validated();

        /** @var int $priceExcludingVat */
        $priceExcludingVat = $request->get('price_excluding_vat');
        /** @var int $vat */
        $vat = $request->get('vat');

        $product->update([...$attributes,
            'price' => $this->calculatePrice(priceExcludingVat: $priceExcludingVat, vat: $vat),
        ]);

        return new ProductResource($product);
    }
}
