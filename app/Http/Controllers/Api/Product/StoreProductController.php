<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Actions\Product\StoreProductAction;
use App\Dto\Product\ProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Responses\Product\StoreProductResponse;

final class StoreProductController extends Controller
{
    public function __invoke(StoreProductRequest $request): StoreProductResponse
    {
        $dto = ProductDto::make(request: $request);

        $product = (new StoreProductAction)->handle(...$dto->toArray());

        return new StoreProductResponse(
            new ProductResource($product)
        );
    }
}
