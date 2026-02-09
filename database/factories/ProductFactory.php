<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price_usd' => $this->faker->randomFloat(2, 1, 1000),
            'price_eur' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}

