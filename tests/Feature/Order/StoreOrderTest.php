<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should be create new order', function () {
    $customer = Customer::factory()->create();
    [$productOne, $productTwo] = Product::factory(2)->create();
    $quantityProductOne = rand(1, 4);
    $quantityProductTwo = rand(1, 4);
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => $productOne->id,
                'quantity' => $quantityProductOne,
            ],
            [
                'product_id' => $productTwo->id,
                'quantity' => $quantityProductTwo,
            ]
        ]
    ])
        ->assertStatus(status: Response::HTTP_CREATED)
        ->assertJson(value: [
            'data' => [
                'ref' => $ref,
                'status' => 'incomplete', // OrderStatusEnum::INCOMPLETE
                'price_excluding_vat' => ($productOne->price_excluding_vat * $quantityProductOne) + ($productTwo->price_excluding_vat * $quantityProductTwo),
                'price' => ($productOne->price * $quantityProductOne) + ($productTwo->price * $quantityProductTwo)
            ]
        ]);

    assertDatabaseHas(table: 'orders', data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'status' => OrderStatusEnum::INCOMPLETE,
        'price_excluding_vat' => ($productOne->price_excluding_vat * $quantityProductOne) + ($productTwo->price_excluding_vat * $quantityProductTwo),
        'price' => ($productOne->price * $quantityProductOne) + ($productTwo->price * $quantityProductTwo)
    ]);
    assertDatabaseHas(table: 'order_product', data: [
        'product_id' => $productOne->id,
        'vat' => $productOne->vat,
        'price_excluding_vat' => $productOne->price_excluding_vat,
        'price' => $productOne->price,
        'quantity' => $quantityProductOne
    ]);
    assertDatabaseHas(table: 'order_product', data: [
        'product_id' => $productTwo->id,
        'vat' => $productTwo->vat,
        'price_excluding_vat' => $productTwo->price_excluding_vat,
        'price' => $productTwo->price,
        'quantity' => $quantityProductTwo
    ]);
});

it('should be return an error if ref is empty', function () {
    $customer = Customer::factory()->create();
    [$productOne, $productTwo] = Product::factory(2)->create();
    $quantityProductOne = rand(1, 4);
    $quantityProductTwo = rand(1, 4);

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => '',
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => $productOne->id,
                'quantity' => $quantityProductOne,
            ],
            [
                'product_id' => $productTwo->id,
                'quantity' => $quantityProductTwo,
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['ref']);
});

it('should be return an error if order ref already exist', function () {
    $customer = Customer::factory()->create();
    $order = Order::factory()->create(['customer_id' => $customer->id]);
    [$productOne, $productTwo] = Product::factory(2)->create();
    $quantityProductOne = rand(1, 4);
    $quantityProductTwo = rand(1, 4);

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $order->ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => $productOne->id,
                'quantity' => $quantityProductOne,
            ],
            [
                'product_id' => $productTwo->id,
                'quantity' => $quantityProductTwo,
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['ref']);
});

it('should be return an error if customer_id is empty', function () {
    [$productOne, $productTwo] = Product::factory(2)->create();
    $quantityProductOne = rand(1, 4);
    $quantityProductTwo = rand(1, 4);
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => null,
        'items' => [
            [
                'product_id' => $productOne->id,
                'quantity' => $quantityProductOne,
            ],
            [
                'product_id' => $productTwo->id,
                'quantity' => $quantityProductTwo,
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['customer_id']);
});

it('should be return an error if customer with id not found', function () {
    [$productOne, $productTwo] = Product::factory(2)->create();
    $quantityProductOne = rand(1, 4);
    $quantityProductTwo = rand(1, 4);
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => 32,
        'items' => [
            [
                'product_id' => $productOne->id,
                'quantity' => $quantityProductOne,
            ],
            [
                'product_id' => $productTwo->id,
                'quantity' => $quantityProductTwo,
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['customer_id']);
});

it('should be return an error if items is not an array', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => null
    ])
        ->assertSessionHasErrors(keys: ['items']);
});

it('should be return an error if items is empty', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => []
    ])
        ->assertSessionHasErrors(keys: ['items']);
});

it('should be return an error if items not have product_id', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => null,
                'quantity' => 1
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['items.0.product_id']);
});


it('should be return an error if items not have product_id valid', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => 12,
                'quantity' => 1
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['items.0.product_id']);
});

it('should be return an error if items not have quantity', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => 12,
                'quantity' => null
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['items.0.quantity']);
});

it('should be return an error if items not have quantity greater than 0', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    userLogin()->post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => 12,
                'quantity' => -1
            ]
        ]
    ])
        ->assertSessionHasErrors(keys: ['items.0.quantity']);
});

it('should be return 302 if user is not auth', function () {
    $customer = Customer::factory()->create();
    $ref = 'ref_' . fake()->uuid;

    post(uri: route('orders.store'), data: [
        'ref' => $ref,
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => 12,
                'quantity' => -1
            ]
        ]
    ])
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
