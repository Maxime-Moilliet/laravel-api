<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Traits\CalculatePriceTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StoreProductController extends Controller
{
    use CalculatePriceTrait;

    public function __invoke(ProductRequest $request): JsonResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->validated();

        /** @var int $priceExcludingVat */
        $priceExcludingVat = $request->get('price_excluding_vat');
        /** @var int $vat */
        $vat = $request->get('vat');

        $product = Product::create([...$attributes,
            'price' => $this->calculatePrice(priceExcludingVat: $priceExcludingVat, vat: $vat),
            'is_archived' => false,
        ]);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(code: Response::HTTP_CREATED);
    }
}
