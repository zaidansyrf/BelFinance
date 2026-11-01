<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeteranganOpsionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengeluaran_bisa_disimpan_tanpa_keterangan(): void
    {
        $this->actingAs(User::factory()->keuangan()->create())
            ->post(route('pengeluaran.store'), [
                'source_id' => Source::factory()->create()->id,
                'bill_id' => Bill::factory()->create()->id,
                'amount' => 50000,
                'date' => '2026-10-27',
                'description' => '',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertNull(Expense::sole()->description);
    }

    public function test_pemasukan_bisa_disimpan_tanpa_keterangan(): void
    {
        $this->actingAs(User::factory()->keuangan()->create())
            ->post(route('uang-masuk.store'), [
                'name' => 'Setoran modal',
                'source_id' => Source::factory()->create()->id,
                'amount' => 100000,
                'date' => '2026-10-27',
                'description' => '',
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertNull(Income::sole()->description);
    }
}
