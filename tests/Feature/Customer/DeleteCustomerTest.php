<?php

declare(strict_types=1);

use App\Models\Customer;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;

it('should be delete customer', function () {
    $customer = Customer::factory()->create();

    assertDatabaseCount(table: Customer::class, count: 1);
    assertModelExists($customer);

    userLogin()->delete(uri: route('customers.delete', $customer->id))
        ->assertStatus(status: 204);

    assertDatabaseCount(table: Customer::class, count: 0);
    assertModelMissing($customer);
});

it('should be return 404 if customer not found', function () {
    userLogin()->delete(uri: route('customers.delete', 1))
        ->assertStatus(status: 404);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create();

    delete(uri: route('customers.delete', $customer->id))
        ->assertStatus(status: 302)
        ->assertLocation(uri: route('login'));
});
