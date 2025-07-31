<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'session_id' => $this->faker->uuid,
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'extras' => $this->faker->optional(0.3)->passthrough([
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
                'color' => $this->faker->colorName,
                'notes' => $this->faker->sentence
            ]),
            'unit_price' => fn (array $attributes) => Product::find($attributes['product_id'])->price,
        ];
    }
}