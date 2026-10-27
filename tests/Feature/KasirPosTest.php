<?php

namespace Tests\Feature;

use App\Models\Income;
use App\Models\Item;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirPosTest extends TestCase
{
    use RefreshDatabase;

    private User $kasir;

    private Source $cash;

    private Source $qris;

    private Item $ayam;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kasir = User::factory()->create(['role' => User::ROLE_KASIR]);
        $this->cash = Source::factory()->create(['name' => 'CASH']);
        $this->qris = Source::factory()->create(['name' => 'QRIS']);
        $this->ayam = Item::factory()->create(['name' => 'KO Ayam Bakar', 'price' => 19000, 'quantity' => 0]);
    }

    public function test_kasir_is_redirected_to_pos_after_login(): void
    {
        $this->post('/login', ['name' => $this->kasir->name, 'password' => 'password'])
            ->assertRedirect(route('kasir.pos'));
    }

    public function test_pos_page_lists_menu(): void
    {
        $this->actingAs($this->kasir)
            ->get(route('kasir.pos'))
            ->assertOk()
            ->assertSee('KO Ayam Bakar')
            ->assertSee('Rp 19.000');
    }

    public function test_cash_checkout_saves_paid_and_change_and_redirects_to_struk(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $this->cash->id,
            'items' => [['item_id' => $this->ayam->id, 'quantity' => 2]],
            'paid_amount' => 50000,
        ]);

        $income = Income::sole();
        $response->assertRedirect(route('kasir.struk', $income));

        $this->assertSame('payment', $income->type);
        $this->assertSame(38000, $income->amount);
        $this->assertSame(50000, $income->paid_amount);
        $this->assertSame(12000, $income->change_amount);
        $this->assertSame($this->kasir->id, $income->created_by);
        $this->assertSame(2, $this->ayam->fresh()->quantity);
    }

    public function test_cash_checkout_rejects_insufficient_payment(): void
    {
        $this->actingAs($this->kasir)
            ->from(route('kasir.pos'))
            ->post(route('kasir.checkout'), [
                'name' => 'Pesanan Offline',
                'source_id' => $this->cash->id,
                'items' => [['item_id' => $this->ayam->id, 'quantity' => 2]],
                'paid_amount' => 20000,
            ])
            ->assertRedirect(route('kasir.pos'))
            ->assertSessionHasErrors('paid_amount');

        $this->assertSame(0, Income::count());
        $this->assertSame(0, $this->ayam->fresh()->quantity);
    }

    public function test_cash_checkout_requires_paid_amount(): void
    {
        $this->actingAs($this->kasir)
            ->post(route('kasir.checkout'), [
                'name' => 'Pesanan Offline',
                'source_id' => $this->cash->id,
                'items' => [['item_id' => $this->ayam->id, 'quantity' => 1]],
            ])
            ->assertSessionHasErrors('paid_amount');
    }

    public function test_non_cash_checkout_ignores_paid_amount(): void
    {
        $this->actingAs($this->kasir)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Online',
            'source_id' => $this->qris->id,
            'items' => [['item_id' => $this->ayam->id, 'quantity' => 1]],
            'paid_amount' => 999999,
        ]);

        $income = Income::sole();
        $this->assertSame(19000, $income->amount);
        $this->assertNull($income->paid_amount);
        $this->assertNull($income->change_amount);
    }

    public function test_price_comes_from_database_not_request(): void
    {
        $this->actingAs($this->kasir)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $this->qris->id,
            'items' => [['item_id' => $this->ayam->id, 'quantity' => 1, 'price' => 1, 'subtotal' => 1]],
        ]);

        $this->assertSame(19000, Income::sole()->amount);
    }

    public function test_checkout_rejects_empty_cart_and_unknown_order_type(): void
    {
        $this->actingAs($this->kasir)
            ->post(route('kasir.checkout'), [
                'name' => 'Pesanan Gratis',
                'source_id' => $this->qris->id,
                'items' => [],
            ])
            ->assertSessionHasErrors(['name', 'items']);
    }

    public function test_pos_sales_appear_in_keuangan_dashboard_and_pembayaran(): void
    {
        $this->actingAs($this->kasir)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $this->qris->id,
            'items' => [['item_id' => $this->ayam->id, 'quantity' => 3]],
        ]);

        $keuangan = User::factory()->keuangan()->create();

        $this->actingAs($keuangan)->get(route('dashboard'))
            ->assertViewHas('total_income', 57000)
            ->assertViewHas('total_orders', 1);

        $this->actingAs($keuangan)->get(route('pembayaran.index'))
            ->assertViewHas('pembayarans', fn ($p) => $p->count() === 1);
    }

    public function test_kasir_can_view_own_struk_but_not_others(): void
    {
        $this->actingAs($this->kasir)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $this->cash->id,
            'items' => [['item_id' => $this->ayam->id, 'quantity' => 1]],
            'paid_amount' => 20000,
        ]);
        $milikSaya = Income::sole();

        $this->actingAs($this->kasir)->get(route('kasir.struk', $milikSaya))
            ->assertOk()
            ->assertSee('KO Ayam Bakar')
            ->assertSee('19.000')
            ->assertSee('1.000'); // kembalian

        $kasirLain = User::factory()->create(['role' => User::ROLE_KASIR]);
        $this->actingAs($kasirLain)->get(route('kasir.struk', $milikSaya))->assertForbidden();

        // Keuangan boleh membuka struk mana pun
        $this->actingAs(User::factory()->keuangan()->create())
            ->get(route('kasir.struk', $milikSaya))
            ->assertOk();
    }

    public function test_kasir_cannot_delete_transactions(): void
    {
        $income = Income::factory()->payment()->create(['created_by' => $this->kasir->id]);

        $this->actingAs($this->kasir)->delete(route('pembayaran.destroy', $income->id))->assertForbidden();
        $this->actingAs($this->kasir)->delete(route('income.destroy', $income->id))->assertForbidden();

        $this->assertNotSoftDeleted($income);
    }

    public function test_kasir_beranda_shows_today_summary(): void
    {
        $this->travelTo(now()->setTime(10, 0));

        $this->actingAs($this->kasir)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $this->cash->id,
            'items' => [['item_id' => $this->ayam->id, 'quantity' => 2]],
            'paid_amount' => 40000,
        ]);
        $kasirLain = User::factory()->kasir()->create();
        // Penjualan kasir lain hari ini
        Income::factory()->payment()->create(['amount' => 10000, 'source_id' => $this->qris->id, 'created_by' => $kasirLain->id]);
        // Penjualan kemarin tidak dihitung
        Income::factory()->payment()->create(['amount' => 99000, 'date' => now()->subDay(), 'created_by' => $kasirLain->id]);

        $this->actingAs($this->kasir)
            ->get(route('kasir.beranda'))
            ->assertOk()
            ->assertViewHas('totalPenjualan', 48000)
            ->assertViewHas('jumlahTransaksi', 2)
            ->assertViewHas('totalSaya', 38000)
            ->assertViewHas('tunaiSaya', 38000)
            ->assertViewHas('menuTerlaris', fn ($m) => $m->first()->name === 'KO Ayam Bakar' && (int) $m->first()->jumlah === 2);
    }
}
