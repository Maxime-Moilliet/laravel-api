<?php

declare(strict_types=1);

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\put;

it('should update product', function () {
    $product = Product::factory()->create();

    $name = fake()->words(3, true);
    $ref = 'ref_'.fake()->uuid;

    $newProduct = [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
        'price' => 11500,
    ];

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertStatus(status: Response::HTTP_OK)
        ->assertJson(value: ['data' => [...$newProduct, 'id' => $product->id]]);

    assertDatabaseCount(table: Product::class, count: 1);
    assertDatabaseMissing(table: Product::class, data: $product->toArray());
    assertDatabaseHas(table: Product::class, data: $newProduct);
});

it('should be return an error if product not found', function () {
    userLogin()->put(uri: route('products.update', 1), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertStatus(status: Response::HTTP_NOT_FOUND);
});

it('should be return an error if name is empty', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => '',
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['name']);
});

it('should be return an error if ref is empty', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => '',
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['ref']);
});

it('should be return an error if vat is empty', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => null,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['vat']);
});

it('should be return an error if vat is not integer', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 'not_integer',
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['vat']);
});

it('should be return an error if vat is not greater than 0', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => -1,
        'price_excluding_vat' => 10000,
    ])
        ->assertSessionHasErrors(keys: ['vat']);
});

it('should be return an error if price excluding vat is empty', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 15,
        'price_excluding_vat' => null,
    ])
        ->assertSessionHasErrors(keys: ['price_excluding_vat']);
});

it('should be return an error if price excluding vat is not integer', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 15,
        'price_excluding_vat' => null,
    ])
        ->assertSessionHasErrors(keys: ['price_excluding_vat']);
});

it('should be return an error if price excluding vat is not greater than 0', function () {
    $product = Product::factory()->create();

    userLogin()->put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 10,
        'price_excluding_vat' => -1,
    ])
        ->assertSessionHasErrors(keys: ['price_excluding_vat']);
});

it('should be return an error if product ref already exist', function () {
    $products = Product::factory(2)->create();

    userLogin()->put(uri: route('products.update', $products->first()), data: [
        'name' => fake()->words(3, true),
        'ref' => $products->last()->ref ?? '',
        'vat' => 15,
        'price_excluding_vat' => null,
    ])
        ->assertSessionHasErrors(keys: ['ref']);
});

it('should be update product if ref is same ref', function () {
    $products = Product::factory(2)->create();
    $name = fake()->words(3, true);
    $ref = $products->first()->ref ?? '';

    $newProduct = [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
        'price' => 11500,
    ];

    userLogin()->put(uri: route('products.update', $products->first()), data: [
        'name' => $name,
        'ref' => $ref,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertStatus(status: Response::HTTP_OK)
        ->assertJson(value: ['data' => [...$newProduct, 'id' => $products->first()->id ?? null]]);

    assertDatabaseCount(table: Product::class, count: 2);
    assertDatabaseMissing(table: Product::class, data: $products->first() ? $products->first()->toArray() : []);
    assertDatabaseHas(table: Product::class, data: $newProduct);
});

it('should be return 302 if user is not auth', function () {
    $product = Product::factory()->create();

    put(uri: route('products.update', $product), data: [
        'name' => fake()->words(3, true),
        'ref' => 'ref_'.fake()->uuid,
        'vat' => 15,
        'price_excluding_vat' => 10000,
    ])
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
