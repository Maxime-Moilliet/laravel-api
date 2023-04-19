<?php

declare(strict_types=1);

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\put;

it('should be archived product', function () {
    $product = Product::factory()->create(['is_archived' => false]);

    $productArchived = [
        'id' => $product->id,
        'name' => $product->name,
        'ref' => $product->ref,
        'vat' => $product->vat,
        'price_excluding_vat' => $product->price_excluding_vat,
        'price' => $product->price,
        'is_archived' => true
    ];

    userLogin()->put(uri: route('products.archived', $product))
        ->assertStatus(status: Response::HTTP_OK)
        ->assertJson(value: [
            'data' => $productArchived,
        ]);

    assertDatabaseCount(table: Product::class, count: 1);
    assertDatabaseMissing(table: Product::class, data: $product->toArray());
    assertDatabaseHas(table: Product::class, data: $productArchived);
});

it('should be return 404 if product not found', function () {
    userLogin()->put(uri: route('products.archived', 1))
        ->assertStatus(status: Response::HTTP_NOT_FOUND);
});

it('should be return 302 if user is not auth', function () {
    $product = Product::factory()->create(['is_archived' => false]);

    put(uri: route('products.archived', $product))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
