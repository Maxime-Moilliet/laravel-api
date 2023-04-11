<?php

declare(strict_types=1);

namespace App\Http\Responses\Product;

use App\Http\Resources\Product\ProductCollection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class ProductCollectionResponse implements Responsable
{
    public function __construct(
        private readonly ProductCollection $productCollection,
        private readonly int $status,
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json(
            data: $this->productCollection->response()->getData(),
            status: $this->status,
        );
    }
}
