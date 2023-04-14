<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Traits\CalculatePriceTrait;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use CalculatePriceTrait;

    public function run(): void
    {
        User::factory(10)->create();
        $customers = Customer::factory(20)->create();

        foreach ($customers as $customer) {
            $orders = Order::factory(2)->create(['customer_id' => $customer->id, 'price' => 0]);

            foreach ($orders as $order) {
                $products = Product::factory(2)->create();

                foreach ($products as $product) {
                    $order->products()->attach($product->id, [
                        'vat' => $product->vat,
                        'price_excluding_vat' => $product->price_excluding_vat,
                        'price' => $product->price,
                    ]);

                    $order->update([
                        'price' => $order->price + $product->price,
                    ]);
                }
            }
        }
    }
}
