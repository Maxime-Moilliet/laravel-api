<?php

declare(strict_types=1);

use App\Models\Customer;

use function Pest\Laravel\get;

it('should see customer', function () {
    $customer = Customer::factory()->create();

    userLogin()->get(uri: route('customers.show', $customer->id))
        ->assertStatus(status: 200)
        ->assertExactJson(data: [
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'note' => $customer->note,
                'is_archived' => $customer->is_archived,
                'created_at' => $customer->created_at,
                'updated_at' => $customer->updated_at,
            ],
        ]);
});

it('should be return 404 if customer not found', function () {
    userLogin()->get(uri: route('customers.show', 1))
        ->assertStatus(status: 404);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create();

    get(uri: route('customers.show', $customer->id))
        ->assertStatus(status: 302)
        ->assertLocation(uri: route('login'));
});
