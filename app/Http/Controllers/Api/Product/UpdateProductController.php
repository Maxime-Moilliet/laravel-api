<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Actions\Product\StoreOrUpdateProductAction;
use App\Dto\Product\ProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreOrUpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Responses\Product\ProductResourceResponse;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

final class UpdateProductController extends Controller
{
    public function __invoke(StoreOrUpdateProductRequest $request, Product $product): ProductResourceResponse
    {
        $dto = ProductDto::make(request: $request);

        $product = (new StoreOrUpdateProductAction($product->id))->handle(...$dto->toArray());

        return new ProductResourceResponse(
            productResource: new ProductResource($product),
            status: Response::HTTP_OK,
        );
    }
}