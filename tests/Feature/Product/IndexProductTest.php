<?php

declare(strict_types=1);

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\get;

it('should be return list of products page 1', function () {
    $products = Product::factory(20)->create();

    $productList = [];
    foreach ($products as $product) {
        $productList[] = [
            'id' => $product->id,
            'name' => $product->name,
            'ref' => $product->ref,
            'vat' => $product->vat,
            'price_excluding_vat' => $product->price_excluding_vat,
            'price' => $product->price,
            'is_archived' => $product->is_archived,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }

    userLogin()->get(uri: route('products.index'))
        ->assertStatus(status: Response::HTTP_OK)
        ->assertExactJson(data: [
            'data' => array_splice($productList, 0, 10),
            'links' => [
                'first' => 'http://localhost/api/products?page=1',
                'last' => 'http://localhost/api/products?page=2',
                'prev' => null,
                'next' => 'http://localhost/api/products?page=2',
            ],
            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 2,
                'links' => [
                    [
                        'url' => null,
                        'label' => '&laquo; Previous',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/products?page=1',
                        'label' => '1',
                        'active' => true,
                    ],
                    [
                        'url' => 'http://localhost/api/products?page=2',
                        'label' => '2',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/products?page=2',
                        'label' => 'Next &raquo;',
                        'active' => false,
                    ],
                ],
                'path' => 'http://localhost/api/products',
                'per_page' => 10,
                'to' => 10,
                'total' => 20,
            ],
        ]);
});

it('should be return list of products page 2', function () {
    $products = Product::factory(20)->create();

    $productList = [];
    foreach ($products as $product) {
        $productList[] = [
            'id' => $product->id,
            'name' => $product->name,
            'ref' => $product->ref,
            'vat' => $product->vat,
            'price_excluding_vat' => $product->price_excluding_vat,
            'price' => $product->price,
            'is_archived' => $product->is_archived,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }

    userLogin()->get(uri: route('products.index', ['page' => 2]))
        ->assertStatus(status: Response::HTTP_OK)
        ->assertExactJson(data: [
            'data' => array_splice($productList, 10, 20),
            'links' => [
                'first' => 'http://localhost/api/products?page=1',
                'last' => 'http://localhost/api/products?page=2',
                'prev' => 'http://localhost/api/products?page=1',
                'next' => null,
            ],
            'meta' => [
                'current_page' => 2,
                'from' => 11,
                'last_page' => 2,
                'links' => [
                    [
                        'url' => 'http://localhost/api/products?page=1',
                        'label' => '&laquo; Previous',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/products?page=1',
                        'label' => '1',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/products?page=2',
                        'label' => '2',
                        'active' => true,
                    ],
                    [
                        'url' => null,
                        'label' => 'Next &raquo;',
                        'active' => false,
                    ],
                ],
                'path' => 'http://localhost/api/products',
                'per_page' => 10,
                'to' => 20,
                'total' => 20,
            ],
        ]);
});

it('should be return 302 if user is not auth', function () {
    get(uri: route('products.index'))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
