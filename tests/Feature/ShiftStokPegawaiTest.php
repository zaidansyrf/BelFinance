<?php

namespace Tests\Feature;

use App\Http\Controllers\AiController;
use App\Models\Income;
use App\Models\Item;
use App\Models\Shift;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ShiftStokPegawaiTest extends TestCase
{
    use RefreshDatabase;

    private function checkout(User $user, Item $item, Source $source, int $qty, ?int $paid = 500000)
    {
        return $this->actingAs($user)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $source->id,
            'items' => [['item_id' => $item->id, 'quantity' => $qty]],
            'paid_amount' => $paid,
        ]);
    }

    // ---------- Shift ----------

    public function test_kasir_without_shift_is_redirected_from_pos_and_checkout(): void
    {
        $kasir = User::factory()->kasir()->create();
        $cash = Source::factory()->create(['name' => 'CASH']);
        $item = Item::factory()->create();

        $this->actingAs($kasir)->get(route('kasir.pos'))->assertRedirect(route('kasir.shift'));
        $this->checkout($kasir, $item, $cash, 1)->assertRedirect(route('kasir.shift'));
        $this->assertDatabaseCount('incomes', 0);
    }

    public function test_kasir_can_open_shift_and_sale_is_linked_to_it(): void
    {
        $kasir = User::factory()->kasir()->create();
        $cash = Source::factory()->create(['name' => 'CASH']);
        $item = Item::factory()->create(['price' => 10000]);

        $this->actingAs($kasir)->post(route('kasir.shift.buka'), ['opening_cash' => 100000])
            ->assertRedirect(route('kasir.pos'));

        $shift = Shift::sole();
        $this->checkout($kasir, $item, $cash, 2, 50000);

        $this->assertSame($shift->id, Income::sole()->shift_id);
    }

    public function test_kasir_cannot_open_second_shift(): void
    {
        $kasir = User::factory()->kasir()->create();
        Shift::create(['user_id' => $kasir->id, 'opened_at' => now(), 'opening_cash' => 0]);

        $this->actingAs($kasir)->post(route('kasir.shift.buka'), ['opening_cash' => 5000])
            ->assertRedirect(route('kasir.shift'));

        $this->assertDatabaseCount('shifts', 1);
    }

    public function test_closing_shift_computes_expected_cash_and_difference_from_cash_sales_only(): void
    {
        $kasir = User::factory()->kasir()->create();
        $cash = Source::factory()->create(['name' => 'CASH']);
        $qris = Source::factory()->create(['name' => 'QRIS']);
        $item = Item::factory()->create(['price' => 10000]);
        Shift::create(['user_id' => $kasir->id, 'opened_at' => now(), 'opening_cash' => 100000]);

        $this->checkout($kasir, $item, $cash, 3, 50000);  // tunai 30.000
        $this->checkout($kasir, $item, $qris, 5, null);   // non-tunai 50.000

        $this->actingAs($kasir)->post(route('kasir.shift.tutup'), ['closing_cash' => 125000])
            ->assertRedirect(route('kasir.shift'));

        $shift = Shift::sole();
        $this->assertNotNull($shift->closed_at);
        $this->assertSame(130000, $shift->expected_cash);
        $this->assertSame(-5000, $shift->difference);

        // Setelah ditutup, kasir harus membuka shift baru untuk berjualan
        $this->actingAs($kasir)->get(route('kasir.pos'))->assertRedirect(route('kasir.shift'));
    }

    public function test_keuangan_sees_shift_history_but_kasir_does_not(): void
    {
        $kasir = User::factory()->kasir()->create();
        Shift::create(['user_id' => $kasir->id, 'opened_at' => now(), 'opening_cash' => 0]);

        $this->actingAs(User::factory()->keuangan()->create())->get(route('shift.index'))
            ->assertOk()->assertSee($kasir->name);
        $this->actingAs($kasir)->get(route('shift.index'))->assertForbidden();
    }

    // ---------- Stok ----------

    public function test_sale_reduces_tracked_stock_and_untracked_stays_null(): void
    {
        $cash = Source::factory()->create(['name' => 'CASH']);
        $dilacak = Item::factory()->create(['stock' => 10, 'price' => 1000]);
        $bebas = Item::factory()->create(['stock' => null, 'price' => 1000]);
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)->post(route('kasir.checkout'), [
            'name' => 'Pesanan Offline',
            'source_id' => $cash->id,
            'items' => [
                ['item_id' => $dilacak->id, 'quantity' => 4],
                ['item_id' => $bebas->id, 'quantity' => 2],
            ],
            'paid_amount' => 10000,
        ])->assertRedirect();

        $this->assertSame(6, $dilacak->fresh()->stock);
        $this->assertNull($bebas->fresh()->stock);
    }

    public function test_sale_is_rejected_when_stock_is_insufficient(): void
    {
        $cash = Source::factory()->create(['name' => 'CASH']);
        $item = Item::factory()->create(['stock' => 2, 'price' => 1000]);

        $this->checkout(User::factory()->owner()->create(), $item, $cash, 3, 10000)
            ->assertSessionHasErrors('items');

        $this->assertSame(2, $item->fresh()->stock);
        $this->assertDatabaseCount('incomes', 0);
    }

    public function test_deleting_a_payment_restores_stock(): void
    {
        $cash = Source::factory()->create(['name' => 'CASH']);
        $item = Item::factory()->create(['stock' => 10, 'price' => 1000]);
        $this->checkout(User::factory()->owner()->create(), $item, $cash, 4, 10000);
        $this->assertSame(6, $item->fresh()->stock);

        Income::sole()->hapus();

        $this->assertSame(10, $item->fresh()->stock);
    }

    public function test_menu_form_saves_stock_and_blank_means_untracked(): void
    {
        $keuangan = User::factory()->keuangan()->create();

        $this->actingAs($keuangan)->post(route('menu.store'), [
            'name' => 'Es Teh', 'price' => 5000, 'quantity' => 0, 'code' => 'ET-1', 'stock' => 12,
        ]);
        $this->actingAs($keuangan)->post(route('menu.store'), [
            'name' => 'Kopi', 'price' => 6000, 'quantity' => 0, 'code' => 'KP-1', 'stock' => '',
        ]);

        $this->assertSame(12, Item::where('code', 'ET-1')->value('stock'));
        $this->assertNull(Item::where('code', 'KP-1')->value('stock'));
    }

    // ---------- Pegawai ----------

    public function test_owner_can_add_update_and_delete_staff(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)->post(route('pegawai.store'), [
            'name' => 'budi', 'email' => 'budi@example.com', 'role' => 'kasir', 'password' => 'rahasia123',
        ])->assertRedirect(route('pegawai.index'));

        $budi = User::where('name', 'budi')->sole();
        $this->assertSame('kasir', $budi->role);

        $this->actingAs($owner)->put(route('pegawai.update', $budi), [
            'name' => 'budi', 'email' => 'budi@example.com', 'role' => 'keuangan', 'password' => '',
        ])->assertRedirect(route('pegawai.index'));
        $this->assertSame('keuangan', $budi->fresh()->role);

        $this->actingAs($owner)->delete(route('pegawai.destroy', $budi))->assertRedirect(route('pegawai.index'));
        $this->assertModelMissing($budi);
    }

    public function test_owner_cannot_create_owner_account_or_touch_owner_users(): void
    {
        $owner = User::factory()->owner()->create();
        $lain = User::factory()->owner()->create();

        $this->actingAs($owner)->post(route('pegawai.store'), [
            'name' => 'x', 'email' => 'x@example.com', 'role' => 'owner', 'password' => 'rahasia123',
        ])->assertSessionHasErrors('role');

        $this->actingAs($owner)->delete(route('pegawai.destroy', $lain))->assertNotFound();
    }

    public function test_non_owner_cannot_manage_staff(): void
    {
        foreach ([User::factory()->keuangan()->create(), User::factory()->kasir()->create()] as $user) {
            $this->actingAs($user)->post(route('pegawai.store'), [
                'name' => 'x', 'email' => 'x@example.com', 'role' => 'kasir', 'password' => 'rahasia123',
            ])->assertForbidden();
        }
    }

    // ---------- AI terbatas untuk keuangan ----------

    private function fakeAi(): void
    {
        config(['services.vikey.base_url' => 'https://api-plan.vikey.ai/v1', 'services.vikey.api_key' => 'test-key']);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => 'Oke.']]]])]);
    }

    public function test_keuangan_cannot_use_dashboard_insight_but_owner_can_reach_it(): void
    {
        $this->fakeAi();
        $this->actingAs(User::factory()->keuangan()->create())->postJson(route('ai.insight'))->assertForbidden();
        $this->actingAs(User::factory()->owner()->create())->postJson(route('ai.insight'))->assertOk();
    }

    public function test_keuangan_has_daily_question_limit_but_owner_does_not(): void
    {
        $this->fakeAi();
        $keuangan = User::factory()->keuangan()->create();
        $owner = User::factory()->owner()->create();
        $key = 'ai-tanya-harian:'.$keuangan->id.':'.now()->toDateString();

        for ($i = 0; $i < AiController::BATAS_HARIAN_KEUANGAN; $i++) {
            RateLimiter::hit($key, 86400);
        }

        $this->actingAs($keuangan)->postJson(route('ai.tanya'), ['pesan' => 'Halo'])->assertStatus(429);
        $this->actingAs($owner)->postJson(route('ai.tanya'), ['pesan' => 'Halo'])->assertOk();
    }
}
