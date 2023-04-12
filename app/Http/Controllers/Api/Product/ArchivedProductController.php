<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Http\Responses\Product\ProductResourceResponse;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

final class ArchivedProductController extends Controller
{
    public function __invoke(Product $product): ProductResourceResponse
    {
        $product->update(['is_archived' => true]);

        return new ProductResourceResponse(
            productResource: new ProductResource($product),
            status: Response::HTTP_OK,
        );
    }
}
