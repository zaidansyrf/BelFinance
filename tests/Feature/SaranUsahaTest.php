<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Item;
use App\Models\Source;
use App\Models\User;
use App\Services\SaranUsahaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SaranUsahaTest extends TestCase
{
    use RefreshDatabase;

    private ?Source $sumber = null;

    private function jual(Item $item, int $qty, string $tanggal, ?Item $bersama = null): void
    {
        $this->sumber ??= Source::factory()->create();
        $income = Income::factory()->payment()->create(['source_id' => $this->sumber->id, 'amount' => $item->price * $qty, 'date' => $tanggal]);
        IncomeDetail::create(['income_id' => $income->id, 'item_id' => $item->id, 'quantity' => $qty, 'subtotal' => $item->price * $qty]);

        if ($bersama) {
            IncomeDetail::create(['income_id' => $income->id, 'item_id' => $bersama->id, 'quantity' => 1, 'subtotal' => $bersama->price]);
        }
    }

    private function judul(array $hasil): string
    {
        return $hasil['saran']->pluck('judul')->implode(' | ');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo('2026-10-30 12:00:00');
    }

    public function test_few_transactions_give_no_sales_advice(): void
    {
        $item = Item::factory()->create();
        $this->jual($item, 1, '2026-10-29');

        $hasil = app(SaranUsahaService::class)->analisis();

        $this->assertFalse($hasil['cukup_data']);
        $this->assertTrue($hasil['saran']->isEmpty());
    }

    public function test_flags_best_seller_and_unsold_menu(): void
    {
        $laris = Item::factory()->create(['name' => 'Nasi Goreng', 'price' => 15000]);
        Item::factory()->create(['name' => 'Menu Sepi', 'price' => 20000]);
        for ($i = 0; $i < 12; $i++) {
            $this->jual($laris, 2, '2026-10-2'.($i % 9));
        }

        $judul = $this->judul(app(SaranUsahaService::class)->analisis());

        $this->assertStringContainsString('Menu paling laris', $judul);
        $this->assertStringContainsString('1 menu tidak terjual sama sekali', $judul);
    }

    public function test_detects_sales_drop_and_rise_against_previous_period(): void
    {
        $turun = Item::factory()->create(['name' => 'Es Teh']);
        $naik = Item::factory()->create(['name' => 'Kopi']);

        // 30 hari sebelumnya: 10 porsi masing-masing
        $this->jual($turun, 10, '2026-09-15');
        $this->jual($naik, 10, '2026-09-15');
        // 30 hari terakhir: Es Teh turun jadi 2, Kopi naik jadi 20 (cukup transaksi agar analisis jalan)
        for ($i = 0; $i < 10; $i++) {
            $this->jual($naik, 2, '2026-10-2'.($i % 9));
        }
        $this->jual($turun, 2, '2026-10-20');

        $judul = $this->judul(app(SaranUsahaService::class)->analisis());

        $this->assertStringContainsString('Es Teh turun 80%', $judul);
        $this->assertStringContainsString('Kopi naik', $judul);
    }

    public function test_suggests_lowering_price_of_expensive_slow_menu(): void
    {
        $a = Item::factory()->create(['name' => 'ALC Ayam', 'price' => 10000]);
        $b = Item::factory()->create(['name' => 'ALC Bebek', 'price' => 10000]);
        $c = Item::factory()->create(['name' => 'ALC Cumi', 'price' => 10000]);
        Item::factory()->create(['name' => 'ALC Mahal', 'price' => 20000]);

        for ($i = 0; $i < 4; $i++) {
            $this->jual($a, 3, '2026-10-2'.$i);
            $this->jual($b, 3, '2026-10-2'.$i);
            $this->jual($c, 3, '2026-10-2'.$i);
        }

        $judul = $this->judul(app(SaranUsahaService::class)->analisis());

        $this->assertStringContainsString('Harga ALC Mahal mungkin terlalu tinggi', $judul);
    }

    public function test_suggests_bundle_for_items_bought_together(): void
    {
        $nasi = Item::factory()->create(['name' => 'Nasi']);
        $teh = Item::factory()->create(['name' => 'Teh']);
        for ($i = 0; $i < 11; $i++) {
            $this->jual($nasi, 1, '2026-10-2'.($i % 9), $i < 4 ? $teh : null);
        }

        $judul = $this->judul(app(SaranUsahaService::class)->analisis());

        $this->assertStringContainsString('Paket hemat: Nasi + Teh', $judul);
    }

    public function test_warns_when_expenses_jump(): void
    {
        $item = Item::factory()->create();
        for ($i = 0; $i < 10; $i++) {
            $this->jual($item, 1, '2026-10-2'.($i % 9));
        }
        $bill = Bill::factory()->create(['name' => 'Listrik']);
        Expense::factory()->create(['source_id' => $this->sumber->id, 'bill_id' => $bill->id, 'amount' => 200000, 'date' => '2026-09-15']);
        Expense::factory()->create(['source_id' => $this->sumber->id, 'bill_id' => $bill->id, 'amount' => 500000, 'date' => '2026-10-15']);

        $hasil = app(SaranUsahaService::class)->analisis();

        $this->assertStringContainsString('Pengeluaran naik 150%', $this->judul($hasil));
        $this->assertStringContainsString('Pos terbesar: Listrik', $hasil['saran']->firstWhere('judul', 'Pengeluaran naik 150%')['detail']);
    }

    public function test_low_stock_warning_appears_even_with_little_data(): void
    {
        Item::factory()->create(['name' => 'Soto', 'stock' => 2]);

        $hasil = app(SaranUsahaService::class)->analisis();

        $this->assertStringContainsString('Stok Soto tinggal 2', $this->judul($hasil));
    }

    public function test_deleted_sales_are_ignored(): void
    {
        $item = Item::factory()->create();
        for ($i = 0; $i < 10; $i++) {
            $this->jual($item, 1, '2026-10-2'.($i % 9));
        }
        Income::query()->update(['deleted_at' => now()]);

        $this->assertFalse(app(SaranUsahaService::class)->analisis()['cukup_data']);
    }

    public function test_only_owner_can_open_saran_page(): void
    {
        $this->actingAs(User::factory()->owner()->create())->get(route('saran.index'))
            ->assertOk()->assertSee('Saran Usaha');
        $this->actingAs(User::factory()->keuangan()->create())->get(route('saran.index'))->assertForbidden();
        $this->actingAs(User::factory()->kasir()->create())->get(route('saran.index'))->assertForbidden();
    }

    public function test_owner_dashboard_shows_saran_card(): void
    {
        $this->actingAs(User::factory()->owner()->create())->get(route('owner-beranda'))
            ->assertOk()->assertSee('Saran Usaha');
    }

    public function test_ai_action_plan_receives_rule_findings_and_menu_data(): void
    {
        config(['services.vikey.base_url' => 'https://api-plan.vikey.ai/v1', 'services.vikey.api_key' => 'test-key']);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => '**Yang sebaiknya dilakukan minggu ini** - tes.']]]])]);

        $nasi = Item::factory()->create(['name' => 'Nasi Goreng', 'price' => 15000]);
        Item::factory()->create(['name' => 'Menu Sepi', 'price' => 20000]);
        for ($i = 0; $i < 12; $i++) {
            $this->jual($nasi, 2, '2026-10-2'.($i % 9));
        }

        $this->actingAs(User::factory()->owner()->create())->postJson(route('ai.saran-usaha'))
            ->assertOk()->assertJsonPath('html', fn ($html) => str_contains($html, 'Yang sebaiknya dilakukan'));

        Http::assertSent(function ($request) {
            $sistem = $request['messages'][0]['content'];

            return str_contains($sistem, '1 menu tidak terjual sama sekali')
                && str_contains($sistem, '"menu": "Nasi Goreng"')
                && str_contains($sistem, '"terjual_30_hari": 24');
        });
    }

    public function test_keuangan_cannot_request_ai_action_plan(): void
    {
        $this->actingAs(User::factory()->keuangan()->create())->postJson(route('ai.saran-usaha'))->assertForbidden();
    }
}
