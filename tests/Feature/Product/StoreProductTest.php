<?php

declare(strict_types=1);

use App\Models\Product;

use function Pest\Faker\fake;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should store new product', function () {
    assertDatabaseCount(table: Product::class, count: 0);

    $name = fake()->words(3, true);
    $ref = 'ref_'.fake()->uuid;

    $product = [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
        'price' => 11500,
        'is_archived' => false,
    ];

    userLogin()->post(uri: route('products.store'), data: [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertStatus(status: 201)
        ->assertJson(value: ['data' => $product]);

    assertDatabaseCount(table: Product::class, count: 1);
    assertDatabaseHas(table: Product::class, data: $product);
});

it('should be return an error if name is empty', function () {
    userLogin()->post(uri: route('products.store'), data: [
        'name' => '',
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['name']);

    assertDatabaseCount(table: Product::class, count: 0);
});

it('should be return an error if ref is empty', function () {
    userLogin()->post(uri: route('products.store'), data: [
        'name' => fake()->words(3, true),
        'ref' => '',
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['ref']);

    assertDatabaseCount(table: Product::class, count: 0);
});

it('should be return an error if vat is empty', function () {
    userLogin()->post(uri: route('products.store'), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => null,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['vat']);

    assertDatabaseCount(table: Product::class, count: 0);
});

it('should be return an error if vat is not integer', function () {
    userLogin()->post(uri: route('products.store'), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 'not_integer',
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['vat']);

    assertDatabaseCount(table: Product::class, count: 0);
});

it('should be return an error if price excluding vat is empty', function () {
    userLogin()->post(uri: route('products.store'), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 20,
        'price_excluding_vat' => null,
    ])
        ->assertSessionHasErrors(keys: ['price_excluding_vat']);

    assertDatabaseCount(table: Product::class, count: 0);
});

it('should be return an error if price excluding vat is not integer', function () {
    userLogin()->post(uri: route('products.store'), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 20,
        'price_excluding_vat' => 'not_integer',
    ])
        ->assertSessionHasErrors(keys: ['price_excluding_vat']);

    assertDatabaseCount(table: Product::class, count: 0);
});

it('should be return an error if product ref already exist', function () {
    $product = Product::factory()->create();

    assertDatabaseCount(table: Product::class, count: 1);

    userLogin()->post(uri: route('products.store'), data: [
        'name' => fake()->words(3, true),
        'ref' => $product->ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['ref']);

    assertDatabaseCount(table: Product::class, count: 1);
});

it('should be return 302 if user is not auth', function () {
    $name = fake()->words(3, true);
    $ref = 'ref_'.fake()->uuid;

    post(uri: route('products.store'), data: [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertStatus(status: 302)
        ->assertLocation(uri: route('login'));
});
