<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
final class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'email' => $this->faker->email,
            'note' => $this->faker->sentences(rand(1, 3), true),
            'is_archived' => $this->faker->boolean(10),
        ];
    }
}
