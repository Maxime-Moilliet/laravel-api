<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Models\Customer;

final class StoreOrUpdateCustomerAction
{
    public function __construct(private readonly ?int $customerId = null)
    {
    }

    public function handle(
        string $name,
        string $email,
        string $note,
    ): Customer {
        return Customer::updateOrCreate(
            ['id' => $this->customerId],
            [
                'name' => $name,
                'email' => $email,
                'note' => $note,
                'is_archived' => false,
            ],
        );
    }
}
