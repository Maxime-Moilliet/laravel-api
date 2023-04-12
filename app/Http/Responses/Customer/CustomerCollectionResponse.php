<?php

declare(strict_types=1);

namespace App\Http\Responses\Customer;

use App\Http\Resources\Customer\CustomerCollection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class CustomerCollectionResponse implements Responsable
{
    public function __construct(
        private readonly CustomerCollection $customerCollection,
        private readonly int $status,
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json(
            data: $this->customerCollection->response()->getData(),
            status: $this->status,
        );
    }
}
