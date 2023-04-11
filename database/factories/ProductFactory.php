<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    public function definition(): array
    {
        $vatArray = [10, 15, 20];
        $vat = $vatArray[array_rand([10, 15, 20])];
        $priceExcludingVat = rand(1000, 10000);
        $price = $priceExcludingVat + ($priceExcludingVat * ($vat / 100));

        return [
            'name' => $this->faker->words(3, true),
            'ref' => 'ref_'.$this->faker->uuid(),
            'vat' => $vat,
            'price_excluding_vat' => $priceExcludingVat,
            'price' => $price,
            'is_archived' => $this->faker->boolean(10),
        ];
    }
}
