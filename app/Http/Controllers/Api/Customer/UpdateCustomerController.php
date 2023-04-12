<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Actions\Customer\StoreOrUpdateCustomerAction;
use App\Dto\Customer\CustomerDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrUpdateCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Responses\Customer\CustomerResourceResponse;
use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

final class UpdateCustomerController extends Controller
{
    public function __invoke(StoreOrUpdateCustomerRequest $request, Customer $customer): CustomerResourceResponse
    {
        $dto = CustomerDto::make(request: $request);

        $customer = (new StoreOrUpdateCustomerAction($customer->id))->handle(...$dto->toArray());

        return new CustomerResourceResponse(
            customerResource: new CustomerResource($customer),
            status: Response::HTTP_OK
        );
    }
}
