<?php

declare(strict_types=1);

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\get;

it('should see product detail', function () {
    $product = Product::factory()->create();

    userLogin()->get(uri: route('products.show', $product))
        ->assertStatus(status: Response::HTTP_OK)
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

it('should be return 404 error if product not found', function () {
    userLogin()->get(uri: route('products.show', 1))
        ->assertStatus(status: Response::HTTP_NOT_FOUND);
});

it('should be return 302 if user is not auth', function () {
    $product = Product::factory()->create();

    get(uri: route('products.show', $product))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
