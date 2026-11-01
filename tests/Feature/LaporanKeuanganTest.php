<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanKeuanganTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Income::factory()->create(['amount' => 100000, 'date' => '2026-10-01']);
        Income::factory()->create(['amount' => 200000, 'date' => '2026-10-15']);
        Expense::factory()->create(['amount' => 50000, 'date' => '2026-10-15']);
        Expense::factory()->create(['amount' => 30000, 'date' => '2026-11-02']);
        Income::factory()->create(['amount' => 999000, 'date' => '2026-10-15'])->delete();
    }

    public function test_laporan_keuangan_combines_income_and_expense(): void
    {
        $this->actingAs(User::factory()->keuangan()->create())
            ->get(route('laporan-keuangan'))
            ->assertOk()
            ->assertViewHas('laporan', fn ($laporan) => $laporan->total() === 4);
    }

    public function test_laporan_filters_by_date_range_inclusive(): void
    {
        $this->actingAs(User::factory()->keuangan()->create())
            ->get(route('laporan-keuangan', ['tanggal_awal' => '2026-10-15', 'tanggal_akhir' => '2026-10-31']))
            ->assertViewHas('laporan', function ($laporan) {
                $rows = collect($laporan->items());

                return $rows->count() === 2
                    && $rows->where('jenis', 'pemasukan')->sum('jumlah') === 200000
                    && $rows->where('jenis', 'pengeluaran')->sum('jumlah') === 50000;
            });
    }

    public function test_owner_laporan_filters_by_type(): void
    {
        $this->actingAs(User::factory()->owner()->create())
            ->get(route('laporan-owner', ['type' => 'pengeluaran']))
            ->assertOk()
            ->assertViewHas('laporan', fn ($laporan) => $laporan->total() === 2
                && collect($laporan->items())->every(fn ($row) => $row->jenis === 'pengeluaran'));
    }

    public function test_export_pdf_downloads_file(): void
    {
        $this->actingAs(User::factory()->owner()->create())
            ->get(route('laporan.export-pdf', ['tanggal_awal' => '2026-10-01', 'tanggal_akhir' => '2026-10-31']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
