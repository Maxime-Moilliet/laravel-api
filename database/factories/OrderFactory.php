<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Traits\CalculatePriceTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
final class OrderFactory extends Factory
{
    use CalculatePriceTrait;

    public function definition(): array
    {
        return [
            'ref' => 'ref_'.$this->faker->uuid(),
            'price' => rand(1000, 50000),
            'status' => OrderStatusEnum::INCOMPLETE
        ];
    }
}
