<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;

final class ShowCustomerController extends Controller
{
    public function __invoke(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }
}
