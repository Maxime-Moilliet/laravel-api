<?php

declare(strict_types=1);

use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;

it('should be delete customer', function () {
    $customer = Customer::factory()->create();

    userLogin()->delete(uri: route('customers.delete', $customer))
        ->assertStatus(status: Response::HTTP_NO_CONTENT);

    assertDatabaseCount(table: Customer::class, count: 0);
    assertModelMissing($customer);
});

it('should be return 404 if customer not found', function () {
    userLogin()->delete(uri: route('customers.delete', 1))
        ->assertStatus(status: Response::HTTP_NOT_FOUND);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create();

    delete(uri: route('customers.delete', $customer))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
