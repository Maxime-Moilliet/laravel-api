<?php

declare(strict_types=1);

use App\Models\Customer;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\put;

it('should be archived customer', function () {
    $customer = Customer::factory()->create(['is_archived' => false]);

    assertDatabaseCount(table: Customer::class, count: 1);

    $customerArchived = [
        'id' => $customer->id,
        'name' => $customer->name,
        'email' => $customer->email,
        'note' => $customer->note,
        'is_archived' => true,
        'created_at' => $customer->created_at,
        'updated_at' => $customer->updated_at,
    ];

    userLogin()->put(uri: route('customers.archived', $customer))
        ->assertStatus(status: 200)
        ->assertExactJson(data: [
            'data' => $customerArchived,
        ]);

    assertDatabaseCount(table: Customer::class, count: 1);
    assertDatabaseMissing(table: Customer::class, data: [$customer]);
    assertDatabaseHas(table: Customer::class, data: $customerArchived);
});

it('should be return 404 if product not found', function () {
    userLogin()->put(uri: route('customers.archived', 1))
        ->assertStatus(status: 404);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create(['is_archived' => false]);

    put(uri: route('customers.archived', $customer))
        ->assertStatus(status: 302)
        ->assertLocation(uri: route('login'));
});
