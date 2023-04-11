<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

final class IndexProductController extends Controller
{
    public function __invoke(Request $request): ProductCollection
    {
        return new ProductCollection(Product::paginate(10));
    }
}
