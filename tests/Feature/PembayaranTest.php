<?php

namespace Tests\Feature;

use App\Models\Income;
use App\Models\Item;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    private User $keuangan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keuangan = User::factory()->keuangan()->create();
    }

    public function test_store_saves_payment_with_details_and_updates_item_quantity(): void
    {
        $source = Source::factory()->create();
        $ayam = Item::factory()->create(['price' => 19000, 'quantity' => 0]);
        $teh = Item::factory()->create(['price' => 5000, 'quantity' => 3]);

        $this->actingAs($this->keuangan)
            ->post(route('pembayaran.store'), [
                'name' => 'Pesanan Offline',
                'source_id' => $source->id,
                'items' => [
                    ['item_id' => $ayam->id, 'quantity' => 2],
                    ['item_id' => $teh->id, 'quantity' => 1],
                ],
            ])
            ->assertRedirect(route('pembayaran.index'));

        $income = Income::sole();
        $this->assertSame('payment', $income->type);
        $this->assertSame(43000, $income->amount);
        $this->assertSame($this->keuangan->id, $income->created_by);
        $this->assertCount(2, $income->incomeDetails);
        $this->assertSame(2, $ayam->fresh()->quantity);
        $this->assertSame(4, $teh->fresh()->quantity);
    }

    public function test_store_saves_payment_with_custom_date(): void
    {
        $source = Source::factory()->create();
        $item = Item::factory()->create(['price' => 15000, 'quantity' => 0]);

        $this->actingAs($this->keuangan)
            ->post(route('pembayaran.store'), [
                'name' => 'Pesanan Offline',
                'date' => '2026-10-25',
                'source_id' => $source->id,
                'items' => [
                    ['item_id' => $item->id, 'quantity' => 1],
                ],
            ])
            ->assertRedirect(route('pembayaran.index'));

        $income = Income::sole();
        $this->assertSame('2026-10-25', $income->date->format('Y-m-d'));
    }

    public function test_store_rejects_future_date(): void
    {
        $source = Source::factory()->create();
        $item = Item::factory()->create(['price' => 15000, 'quantity' => 0]);

        $futureDate = now()->addDays(2)->format('Y-m-d');

        $this->actingAs($this->keuangan)
            ->post(route('pembayaran.store'), [
                'name' => 'Pesanan Offline',
                'date' => $futureDate,
                'source_id' => $source->id,
                'items' => [
                    ['item_id' => $item->id, 'quantity' => 1],
                ],
            ])
            ->assertSessionHasErrors('date');

        $this->assertSame(0, Income::count());
    }

    public function test_index_lists_only_payments(): void
    {
        Income::factory()->payment()->create(['name' => 'Pesanan Online']);
        Income::factory()->create(['name' => 'Suntikan Modal']);

        $this->actingAs($this->keuangan)
            ->get(route('pembayaran.index'))
            ->assertOk()
            ->assertViewHas('pembayarans', fn ($p) => $p->count() === 1 && $p->first()->name === 'Pesanan Online');
    }

    public function test_index_filters_by_month_year_and_sorts(): void
    {
        Income::factory()->payment()->create(['date' => '2026-05-10', 'name' => 'Pesanan Mei']);
        Income::factory()->payment()->create(['date' => '2026-06-15', 'name' => 'Pesanan Juni']);
        Income::factory()->payment()->create(['date' => '2025-06-20', 'name' => 'Pesanan Tahun Lalu']);

        $this->actingAs($this->keuangan)
            ->get(route('pembayaran.index', ['bulan' => 6, 'tahun' => 2026]))
            ->assertOk()
            ->assertViewHas('pembayarans', fn ($p) => $p->total() === 1 && $p->first()->name === 'Pesanan Juni');

        $this->actingAs($this->keuangan)
            ->get(route('pembayaran.index', ['sort' => 'asc']))
            ->assertOk()
            ->assertViewHas('pembayarans', fn ($p) => $p->first()->name === 'Pesanan Tahun Lalu');
    }

    public function test_destroy_soft_deletes_payment_and_reverts_item_quantity(): void
    {
        $item = Item::factory()->create(['price' => 10000, 'quantity' => 0]);
        $this->actingAs($this->keuangan)->post(route('pembayaran.store'), [
            'name' => 'Pesanan Offline',
            'source_id' => Source::factory()->create()->id,
            'items' => [['item_id' => $item->id, 'quantity' => 3]],
        ]);
        $income = Income::sole();

        $this->actingAs($this->keuangan)
            ->delete(route('pembayaran.destroy', $income->id))
            ->assertRedirect(route('pembayaran.index'));

        $this->assertSoftDeleted($income);
        $this->assertSame($this->keuangan->id, Income::withTrashed()->find($income->id)->deleted_by);
        $this->assertSame(1, $income->incomeDetails()->count(), 'detail pesanan tetap disimpan');
        $this->assertSame(0, $item->fresh()->quantity);
    }

    public function test_deleting_payment_from_uang_masuk_also_reverts_item_quantity(): void
    {
        $item = Item::factory()->create(['price' => 10000, 'quantity' => 0]);
        $this->actingAs($this->keuangan)->post(route('pembayaran.store'), [
            'name' => 'Pesanan Offline',
            'source_id' => Source::factory()->create()->id,
            'items' => [['item_id' => $item->id, 'quantity' => 2]],
        ]);

        $this->actingAs($this->keuangan)->delete(route('income.destroy', Income::sole()));

        $this->assertSame(0, Income::count());
        $this->assertSame(0, $item->fresh()->quantity);
    }

    public function test_promo_pages_can_be_rendered(): void
    {
        $this->actingAs($this->keuangan)
            ->get(route('promo.item.index'))
            ->assertOk()
            ->assertSee('Promo Menu Merchant');

        $this->actingAs($this->keuangan)
            ->get(route('promo.platform.index'))
            ->assertOk()
            ->assertSee('Promo Platform');
    }

    public function test_hybrid_promo_system_calculates_exact_financial_breakdown(): void
    {
        // 1. Setup GoFood: is_online = true, komisi 20%
        $gofood = Source::factory()->create([
            'name' => 'GoFood',
            'is_online' => true,
            'default_commission_percent' => 20.0,
        ]);

        // 2. Setup Voucher GoFood Hemat 15rb (Nominal 15.000, Merchant 40%, App 60%)
        $voucher = \App\Models\PlatformPromo::create([
            'source_id' => $gofood->id,
            'name' => 'GoFood Hemat Rp 15.000',
            'discount_type' => 'nominal',
            'discount_value' => 15000,
            'merchant_share_percent' => 40.0,
            'is_active' => true,
        ]);

        // 3. Setup Promo Menu: Diskon Rp 5.000/porsi
        $itemPromo = \App\Models\ItemPromo::create([
            'name' => 'Harga Coret Menu Baru',
            'discount_type' => 'nominal',
            'discount_value' => 5000,
            'channel_scope' => 'all',
            'is_active' => true,
        ]);

        // 4. Setup Menu: Ayam Bakar @ Rp 30.000, terikat ke promo menu di atas
        $ayamBakar = Item::factory()->create([
            'name' => 'Ayam Bakar',
            'price' => 30000,
            'quantity' => 0,
            'item_promo_id' => $itemPromo->id,
        ]);

        // 5. Transaksi: 2 porsi Ayam Bakar via GoFood dengan voucher
        $this->actingAs($this->keuangan)
            ->post(route('pembayaran.store'), [
                'name' => 'Pesanan Online',
                'source_id' => $gofood->id,
                'platform_promo_id' => $voucher->id,
                'items' => [
                    [
                        'item_id' => $ayamBakar->id,
                        'quantity' => 2,
                        'apply_item_promo' => true,
                    ],
                ],
            ])
            ->assertRedirect(route('pembayaran.index'));

        $income = Income::sole();

        // Verifikasi hasil sesuai Simulasi Kasus Nyata di Bagian 3 Spesifikasi
        $this->assertSame(60000, $income->gross_amount, 'Gross amount = 2 x 30.000');
        $this->assertSame(10000, $income->item_discount_total, 'Total diskon menu = 2 x 5.000');
        $this->assertSame(50000, $income->subtotalAfterItemPromo(), 'Subtotal setelah diskon menu = 50.000');

        $this->assertSame(15000, $income->platform_promo_total, 'Voucher platform total = 15.000');
        $this->assertSame(6000, $income->platform_merchant_share, 'Beban voucher merchant 40% = 6.000');
        $this->assertSame(9000, $income->platform_app_subsidy, 'Subsidi voucher app 60% = 9.000');

        $this->assertSame(35000, $income->customer_paid_amount, 'Dibayar pembeli = 50.000 - 15.000');
        $this->assertSame(10000, $income->commission_amount, 'Komisi GoFood 20% x 50.000 = 10.000');
        $this->assertSame(34000, $income->amount, 'Net payout = 50.000 - 6.000 - 10.000');

        // Verifikasi detail baris item
        $detail = $income->incomeDetails->first();
        $this->assertSame($itemPromo->id, $detail->item_promo_id);
        $this->assertSame(30000, $detail->original_price);
        $this->assertSame(5000, $detail->discount_amount);
        $this->assertSame(25000, $detail->final_price);
        $this->assertSame(50000, $detail->subtotal);
    }
}
