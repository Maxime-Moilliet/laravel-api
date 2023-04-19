<?php

declare(strict_types=1);

use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should store new customer', function () {
    $name = fake()->words(3, true);
    $email = fake()->email;
    $note = fake()->paragraphs(rand(1, 3), true);

    $customer = [
        'name' => $name,
        'email' => $email,
        'note' => $note,
        'is_archived' => false,
    ];

    userLogin()->post(uri: route('customers.store'), data: [
        'name' => $name,
        'email' => $email,
        'note' => $note,
    ])
        ->assertStatus(status: Response::HTTP_CREATED)
        ->assertJson(value: ['data' => $customer]);

    assertDatabaseCount(table: Customer::class, count: 1);
    assertDatabaseHas(table: Customer::class, data: $customer);
});

it('should be return an error if name is empty', function () {
    userLogin()->post(uri: route('customers.store'), data: [
        'name' => '',
        'email' => fake()->email,
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertSessionHasErrors(keys: ['name']);

    assertDatabaseCount(table: Customer::class, count: 0);
});

it('should be return an error if email is empty', function () {
    userLogin()->post(uri: route('customers.store'), data: [
        'name' => fake()->words(3, true),
        'email' => '',
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertSessionHasErrors(keys: ['email']);

    assertDatabaseCount(table: Customer::class, count: 0);
});

it('should be return an error if email is not an email', function () {
    userLogin()->post(uri: route('customers.store'), data: [
        'name' => fake()->words(3, true),
        'email' => fake()->words(3, true),
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertSessionHasErrors(keys: ['email']);

    assertDatabaseCount(table: Customer::class, count: 0);
});

it('should be return an error if note is empty', function () {
    userLogin()->post(uri: route('customers.store'), data: [
        'name' => fake()->words(3, true),
        'email' => fake()->email,
        'note' => '',
    ])
        ->assertSessionHasErrors(keys: ['note']);

    assertDatabaseCount(table: Customer::class, count: 0);
});

it('should be return 302 if user is not auth', function () {
    post(uri: route('customers.store'), data: [
        'name' => fake()->words(3, true),
        'email' => fake()->email,
        'note' => fake()->paragraphs(rand(1, 3), true),
    ])
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
