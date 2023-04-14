<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerCollection;
use App\Models\Customer;

final class IndexCustomerController extends Controller
{
    public function __invoke(): CustomerCollection
    {
        return new CustomerCollection(Customer::paginate(10));
    }
}
