<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StoreCustomerController extends Controller
{
    public function __invoke(CustomerRequest $request): JsonResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->validated();

        $customer = Customer::create([...$attributes, 'is_archived' => false]);

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(code: Response::HTTP_CREATED);
    }
}
