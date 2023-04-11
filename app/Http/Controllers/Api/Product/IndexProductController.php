<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Responses\Product\ProductCollectionResponse;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IndexProductController extends Controller
{
    public function __invoke(Request $request): ProductCollectionResponse
    {
        return new ProductCollectionResponse(
            productCollection: new ProductCollection(Product::paginate(10)),
            status: Response::HTTP_OK
        );
    }
}
