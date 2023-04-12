<?php

declare(strict_types=1);

use App\Models\Product;

use function Pest\Laravel\get;

it('should see product detail', function () {
    $product = Product::factory()->create();

    userLogin()->get(uri: '/api/products/'.$product->id)
        ->assertStatus(status: 200)
        ->assertExactJson(data: ['data' => [
            'id' => $product->id,
            'name' => $product->name,
            'ref' => $product->ref,
            'vat' => $product->vat,
            'price_excluding_vat' => $product->price_excluding_vat,
            'price' => $product->price,
            'is_archived' => $product->is_archived,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]]);
});

it('should be return 404 error if product not exist', function () {
    userLogin()->get(uri: '/api/products/1')
        ->assertStatus(status: 404);
});

it('should be return 302 if user is not auth', function () {
    get(uri: '/api/products/1')
        ->assertStatus(status: 302)
        ->assertLocation(uri: '/api/login');
});
