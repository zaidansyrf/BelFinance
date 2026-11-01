<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'source_id' => Source::factory(),
            'bill_id' => Bill::factory(),
            'amount' => fake()->numberBetween(10, 500) * 1000,
            'date' => now()->toDateString(),
            'description' => fake()->sentence(),
        ];
    }
}
