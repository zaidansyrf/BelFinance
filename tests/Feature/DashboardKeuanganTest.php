<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardKeuanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_totals_use_transaction_date_not_input_time(): void
    {
        $this->travelTo('2026-10-27 10:00:00');

        // Diinput hari ini, tapi transaksinya kemarin -> tidak masuk "hari ini"
        Income::factory()->create(['amount' => 100000, 'date' => '2026-10-26']);
        // Transaksi hari ini
        Income::factory()->payment()->create(['amount' => 40000, 'date' => '2026-10-27']);
        Expense::factory()->create(['amount' => 15000, 'date' => '2026-10-27']);
        // Transaksi bulan lalu
        Expense::factory()->create(['amount' => 5000, 'date' => '2026-09-10']);

        $this->actingAs(User::factory()->keuangan()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('total_income', 40000)
            ->assertViewHas('total_expenses', 15000)
            ->assertViewHas('profit', 25000)
            ->assertViewHas('total_orders', 1)
            ->assertViewHas('yearlyIncome', 140000)
            ->assertViewHas('yearlyExpense', 20000)
            ->assertViewHas('incomeData', fn ($data) => $data[9] === 140000 && array_sum($data) === 140000)
            ->assertViewHas('expenseData', fn ($data) => $data[8] === 5000 && $data[9] === 15000);
    }

    public function test_soft_deleted_transactions_are_excluded(): void
    {
        $this->travelTo('2026-10-27 10:00:00');

        Income::factory()->create(['amount' => 50000]);
        Income::factory()->create(['amount' => 70000])->delete();

        $this->actingAs(User::factory()->keuangan()->create())
            ->get(route('dashboard'))
            ->assertViewHas('total_income', 50000);
    }

    public function test_owner_beranda_shows_same_numbers(): void
    {
        $this->travelTo('2026-10-27 10:00:00');

        Income::factory()->create(['amount' => 30000]);
        Expense::factory()->create(['amount' => 10000]);

        $this->actingAs(User::factory()->owner()->create())
            ->get(route('owner-beranda'))
            ->assertOk()
            ->assertViewHas('total_income', 30000)
            ->assertViewHas('total_expenses', 10000)
            ->assertViewHas('profit', 20000)
            ->assertViewHas('chartData');
    }
}
