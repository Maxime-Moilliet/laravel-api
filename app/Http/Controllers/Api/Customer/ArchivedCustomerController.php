<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Responses\Customer\CustomerResourceResponse;
use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

final class ArchivedCustomerController extends Controller
{
    public function __invoke(Customer $customer): CustomerResourceResponse
    {
        $customer->update(['is_archived' => true]);

        return new CustomerResourceResponse(
            customerResource: new CustomerResource($customer),
            status: Response::HTTP_OK,
        );
    }
}
