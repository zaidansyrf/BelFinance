<?php

namespace Database\Factories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'source_id' => Source::factory(),
            'name' => 'Pemasukan lain',
            'amount' => fake()->numberBetween(10, 500) * 1000,
            'date' => now()->toDateString(),
            'description' => fake()->sentence(),
            'type' => 'income',
        ];
    }

    public function payment(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Pesanan Offline',
            'type' => 'payment',
        ]);
    }
}
