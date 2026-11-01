<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
class SourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['CASH', 'BNI', 'BTN', 'GOJEK', 'GRAB', 'SHOPEE', 'QRIS', 'BCA', 'BRI', 'MANDIRI']),
        ];
    }
}
