<?php

declare(strict_types=1);

namespace App\Http\Responses\Product;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StoreProductResponse implements Responsable
{
    public function __construct(
        private readonly ProductResource $productResource,
        private readonly int $status = Response::HTTP_CREATED,
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
