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

    public function test_index_lists_only_payments(): void
    {
        Income::factory()->payment()->create(['name' => 'Pesanan Online']);
        Income::factory()->create(['name' => 'Suntikan Modal']);

        $this->actingAs($this->keuangan)
            ->get(route('pembayaran.index'))
            ->assertOk()
            ->assertViewHas('pembayarans', fn ($p) => $p->count() === 1 && $p->first()->name === 'Pesanan Online');
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
}
