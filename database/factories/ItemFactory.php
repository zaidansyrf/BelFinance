<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('MN-###'),
            'name' => fake()->unique()->words(2, true),
            'price' => fake()->numberBetween(5, 50) * 1000,
            'quantity' => 0,
        ];
    }
}
