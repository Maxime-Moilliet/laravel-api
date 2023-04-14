<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;

final class ArchivedProductController extends Controller
{
    public function __invoke(Product $product): ProductResource
    {
        $product->update(['is_archived' => true]);

        return new ProductResource($product);
    }
}
