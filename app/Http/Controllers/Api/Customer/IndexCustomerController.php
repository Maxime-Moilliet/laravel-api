<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Responses\Customer\CustomerCollectionResponse;
use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

final class IndexCustomerController extends Controller
{
    public function __invoke(): CustomerCollectionResponse
    {
        return new CustomerCollectionResponse(
            customerCollection: new CustomerCollection(Customer::paginate(10)),
            status: Response::HTTP_OK
        );
    }
}
