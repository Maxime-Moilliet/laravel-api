<?php

declare(strict_types=1);

namespace App\Http\Responses\Product;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class ProductResourceResponse implements Responsable
{
    public function __construct(
        private readonly ProductResource $productResource,
        private readonly int $status,
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json(
            data: ['data' => $this->productResource],
            status: $this->status,
        );
    }
}
