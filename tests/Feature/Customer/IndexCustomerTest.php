<?php

declare(strict_types=1);

use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\get;

it('should be return list of customers page 1', function () {
    $customers = Customer::factory(20)->create();

    $customerList = [];
    foreach ($customers as $customer) {
        $customerList[] = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'note' => $customer->note,
            'is_archived' => $customer->is_archived,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ];
    }

    userLogin()->get(uri: route('customers.index'))
        ->assertStatus(status: Response::HTTP_OK)
        ->assertExactJson(data: [
            'data' => array_splice($customerList, 0, 10),
            'links' => [
                'first' => 'http://localhost/api/customers?page=1',
                'last' => 'http://localhost/api/customers?page=2',
                'prev' => null,
                'next' => 'http://localhost/api/customers?page=2',
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
                        'url' => 'http://localhost/api/customers?page=1',
                        'label' => '1',
                        'active' => true,
                    ],
                    [
                        'url' => 'http://localhost/api/customers?page=2',
                        'label' => '2',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/customers?page=2',
                        'label' => 'Next &raquo;',
                        'active' => false,
                    ],
                ],
                'path' => 'http://localhost/api/customers',
                'per_page' => 10,
                'to' => 10,
                'total' => 20,
            ],
        ]);
});

it('should be return list of customers page 2', function () {
    $customers = Customer::factory(20)->create();

    $customerList = [];
    foreach ($customers as $customer) {
        $customerList[] = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'note' => $customer->note,
            'is_archived' => $customer->is_archived,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ];
    }

    userLogin()->get(uri: route('customers.index', ['page' => 2]))
        ->assertStatus(status: Response::HTTP_OK)
        ->assertExactJson(data: [
            'data' => array_splice($customerList, 10, 20),
            'links' => [
                'first' => 'http://localhost/api/customers?page=1',
                'last' => 'http://localhost/api/customers?page=2',
                'prev' => 'http://localhost/api/customers?page=1',
                'next' => null,
            ],
            'meta' => [
                'current_page' => 2,
                'from' => 11,
                'last_page' => 2,
                'links' => [
                    [
                        'url' => 'http://localhost/api/customers?page=1',
                        'label' => '&laquo; Previous',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/customers?page=1',
                        'label' => '1',
                        'active' => false,
                    ],
                    [
                        'url' => 'http://localhost/api/customers?page=2',
                        'label' => '2',
                        'active' => true,
                    ],
                    [
                        'url' => null,
                        'label' => 'Next &raquo;',
                        'active' => false,
                    ],
                ],
                'path' => 'http://localhost/api/customers',
                'per_page' => 10,
                'to' => 20,
                'total' => 20,
            ],
        ]);
});

it('should be return 302 if user is not auth', function () {
    get(uri: route('customers.index'))
        ->assertStatus(status: Response::HTTP_FOUND)
        ->assertLocation(uri: route('login'));
});
