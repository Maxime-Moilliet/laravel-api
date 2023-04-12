<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCustomerController extends Controller
{
    public function __invoke(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
