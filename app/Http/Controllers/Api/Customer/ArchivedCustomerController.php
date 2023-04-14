<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;

final class ArchivedCustomerController extends Controller
{
    public function __invoke(Customer $customer): CustomerResource
    {
        $customer->update(['is_archived' => true]);

        return new CustomerResource($customer);
    }
}
