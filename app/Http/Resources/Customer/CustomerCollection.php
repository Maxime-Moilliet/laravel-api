<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class CustomerCollection extends ResourceCollection
{
    public $collects = CustomerResource::class;

    /**
     * @return array<string, mixed>
     */
    public function toArray(
        Request $request,
    ): array {
        return [
            'data' => $this->collection,
        ];
    }
}
