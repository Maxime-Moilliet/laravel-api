<?php

namespace App\Http\Responses\Customer;

use App\Http\Resources\Customer\CustomerResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class CustomerResourceResponse implements Responsable
{
    public function __construct(
        private readonly CustomerResource $customerResource,
        private readonly int $status,
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json(
            data: ['data' => $this->customerResource],
            status: $this->status,
        );
    }
}
