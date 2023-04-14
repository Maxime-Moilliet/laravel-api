<?php

declare(strict_types=1);

use App\Models\Customer;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\put;

it('should update customer', function () {
    $customer = Customer::factory()->create();

    $name = fake()->words(3, true);
    $email = fake()->email;
    $note = fake()->paragraphs(rand(1, 3), true);

    $newCustomer = [
        'name' => $name,
        'email' => $email,
        'note' => $note,
    ];

    userLogin()->put(uri: route('customers.update', $customer), data: [
        'name' => $name,
        'email' => $email,
        'note' => $note,
    ])
        ->assertStatus(status: 200)
        ->assertJson(value: ['data' => $newCustomer]);

    assertDatabaseCount(table: Customer::class, count: 1);
    assertDatabaseMissing(table: Customer::class, data: [$customer]);
    assertDatabaseHas(table: Customer::class, data: $newCustomer);
});

it('should be return 404 if customer not found', function () {
    $name = fake()->words(3, true);
    $email = fake()->email;
    $note = fake()->paragraphs(rand(1, 3), true);

    userLogin()->put(uri: route('customers.update', 1), data: [
        'name' => $name,
        'email' => $email,
        'note' => $note,
    ])
        ->assertStatus(status: 404);
});

it('should be return an error if name is empty', function () {
    $customer = Customer::factory()->create();

    userLogin()->put(uri: route('customers.update', $customer), data: [
        'name' => '',
        'email' => fake()->email,
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertSessionHasErrors(keys: ['name']);
});

it('should be return an error if email is empty', function () {
    $customer = Customer::factory()->create();

    userLogin()->put(uri: route('customers.update', $customer), data: [
        'name' => fake()->words(3, true),
        'email' => '',
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertSessionHasErrors(keys: ['email']);
});

it('should be return an error if email is not an email', function () {
    $customer = Customer::factory()->create();

    userLogin()->put(uri: route('customers.update', $customer), data: [
        'name' => fake()->words(3, true),
        'email' => fake()->words(3, true),
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertSessionHasErrors(keys: ['email']);
});

it('should be return an error if note is empty', function () {
    $customer = Customer::factory()->create();

    userLogin()->put(uri: route('customers.update', $customer), data: [
        'name' => fake()->words(3, true),
        'email' => fake()->email,
        'note' => '',
    ])
        ->assertSessionHasErrors(keys: ['note']);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create();

    put(uri: route('customers.update', $customer), data: [
        'name' => fake()->words(3, true),
        'email' => fake()->email,
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertStatus(status: 302)
        ->assertLocation(uri: route('login'));
});
