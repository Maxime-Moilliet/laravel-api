<?php

declare(strict_types=1);

use App\Models\Product;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;

it('should be delete product', function () {
    $product = Product::factory()->create();

    assertDatabaseCount(table: Product::class, count: 1);
    assertModelExists($product);

    userLogin()->delete('/api/products/'.$product->id)
        ->assertStatus(204);

    assertDatabaseCount(table: Product::class, count: 0);
    assertModelMissing($product);
});

it('should be return 404 if product not found', function () {
    userLogin()->delete('/api/products/1')
        ->assertStatus(404);
});

it('should be return 302 if user is not auth', function () {
    delete(uri: '/api/products/1')
        ->assertStatus(status: 302)
        ->assertLocation(uri: '/api/login');
});
