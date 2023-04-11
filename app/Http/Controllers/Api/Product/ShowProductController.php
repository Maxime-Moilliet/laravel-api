<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;

final class ShowProductController extends Controller
{
    public function __invoke(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
