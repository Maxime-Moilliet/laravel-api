<?php

declare(strict_types=1);

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;

it('should be delete product', function () {
    $product = Product::factory()->create();

    userLogin()->delete(uri: route('products.delete', $product))
        ->assertStatus(status: Response::HTTP_NO_CONTENT);

    assertDatabaseCount(table: Product::class, count: 0);
    assertModelMissing($product);
});

it('should be return 404 if product not found', function () {
    userLogin()->delete(uri: route('products.delete', 1))
        ->assertStatus(status: Response::HTTP_NOT_FOUND);
});

it('should be return 302 if user is not auth', function () {
    $product = Product::factory()->create();

    delete(uri: route('products.delete', $product))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
