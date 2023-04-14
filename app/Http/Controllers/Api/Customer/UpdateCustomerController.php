<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;

final class UpdateCustomerController extends Controller
{
    public function __invoke(CustomerRequest $request, Customer $customer): CustomerResource
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->validated();

        $customer->update($attributes);

        return new CustomerResource($customer);
    }
}
