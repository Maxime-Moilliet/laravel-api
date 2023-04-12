<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Actions\Customer\StoreOrUpdateCustomerAction;
use App\Dto\Customer\CustomerDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrUpdateCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Responses\Customer\CustomerResourceResponse;
use Symfony\Component\HttpFoundation\Response;

final class StoreCustomerController extends Controller
{
    public function __invoke(StoreOrUpdateCustomerRequest $request): CustomerResourceResponse
    {
        $dto = CustomerDto::make(request: $request);

        $customer = (new StoreOrUpdateCustomerAction)->handle(...$dto->toArray());

        return new CustomerResourceResponse(
            customerResource: new CustomerResource($customer),
            status: Response::HTTP_CREATED,
        );
    }
}
