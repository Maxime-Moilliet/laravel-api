<?php

declare(strict_types=1);

use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\put;

it('should be archived customer', function () {
    $customer = Customer::factory()->create(['is_archived' => false]);

    $customerArchived = [
        'id' => $customer->id,
        'name' => $customer->name,
        'email' => $customer->email,
        'note' => $customer->note,
        'is_archived' => true,
    ];

    userLogin()->put(uri: route('customers.archived', $customer))
        ->assertStatus(status: Response::HTTP_OK)
        ->assertJson(value: [
            'data' => $customerArchived,
        ]);

    assertDatabaseCount(table: Customer::class, count: 1);
    assertDatabaseMissing(table: Customer::class, data: $customer->toArray());
    assertDatabaseHas(table: Customer::class, data: $customerArchived);
});

it('should be return 404 if product not found', function () {
    userLogin()->put(uri: route('customers.archived', 1))
        ->assertStatus(status: Response::HTTP_NOT_FOUND);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create(['is_archived' => false]);

    put(uri: route('customers.archived', $customer))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
