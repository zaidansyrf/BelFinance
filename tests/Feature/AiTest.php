<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Item;
use App\Models\Source;
use App\Models\User;
use App\Services\Ai\KonteksKeuangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiTest extends TestCase
{
    use RefreshDatabase;

    private const URL = 'https://api-plan.vikey.ai/v1/chat/completions';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.vikey.base_url' => 'https://api-plan.vikey.ai/v1',
            'services.vikey.api_key' => 'test-key',
            'services.vikey.model' => 'vikey/plan',
        ]);
    }

    private function fakeJawaban(string $isi): void
    {
        Http::fake([self::URL => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => $isi]]]])]);
    }

    public function test_tanya_sends_financial_context_and_returns_safe_html(): void
    {
        $this->travelTo('2026-10-27 10:00:00');
        Income::factory()->payment()->create(['amount' => 123000, 'date' => '2026-10-27']);
        $this->fakeJawaban("Penjualan hari ini **Rp 123.000**.\n\n<script>alert(1)</script>");

        $response = $this->actingAs(User::factory()->owner()->create())
            ->postJson(route('ai.tanya'), [
                'pesan' => 'Berapa penjualan hari ini?',
                'riwayat' => [
                    ['role' => 'user', 'content' => 'Halo'],
                    ['role' => 'assistant', 'content' => 'Halo, ada yang bisa dibantu?'],
                ],
            ])
            ->assertOk();

        $this->assertStringContainsString('<strong>Rp 123.000</strong>', $response->json('html'));
        $this->assertStringNotContainsString('<script>', $response->json('html'));

        Http::assertSent(function (Request $request) {
            $messages = $request['messages'];

            return $request->hasHeader('Authorization', 'Bearer test-key')
                && $request['model'] === 'vikey/plan'
                && $messages[0]['role'] === 'system'
                && str_contains($messages[0]['content'], '"pemasukan_dari_penjualan": 123000')
                && $messages[1]['content'] === 'Halo'
                && end($messages)['content'] === 'Berapa penjualan hari ini?';
        });
    }

    public function test_think_tags_are_removed_from_answer(): void
    {
        $this->fakeJawaban('<think>menghitung...</think>Jawaban akhir.');

        $this->actingAs(User::factory()->keuangan()->create())
            ->postJson(route('ai.tanya'), ['pesan' => 'Tes'])
            ->assertJsonPath('jawaban', 'Jawaban akhir.');
    }

    public function test_retries_with_bigger_budget_when_reasoning_uses_all_tokens(): void
    {
        $habis = ['choices' => [['message' => ['content' => '[No answer: the model spent its entire max_tokens budget on reasoning before producing a reply.]']]]];
        Http::fakeSequence(self::URL)
            ->push($habis)
            ->push(['choices' => [['message' => ['content' => 'Jawaban lengkap.']]]]);

        $this->actingAs(User::factory()->owner()->create())
            ->postJson(route('ai.tanya'), ['pesan' => 'Tes'])
            ->assertOk()
            ->assertJsonPath('jawaban', 'Jawaban lengkap.');

        $recorded = Http::recorded();
        $this->assertCount(2, $recorded);
        $this->assertSame($recorded[0][0]['max_tokens'] * 2, $recorded[1][0]['max_tokens']);
    }

    public function test_gives_friendly_error_when_still_out_of_tokens(): void
    {
        Http::fake([self::URL => Http::response(['choices' => [['message' => ['content' => '[No answer: budget habis]']]]])]);

        $this->actingAs(User::factory()->owner()->create())
            ->postJson(route('ai.tanya'), ['pesan' => 'Tes'])
            ->assertStatus(503)
            ->assertJsonPath('pesan', 'AI tidak sempat menyelesaikan jawaban. Coba pertanyaan yang lebih singkat.');
    }

    public function test_missing_api_key_returns_friendly_error(): void
    {
        config(['services.vikey.api_key' => null]);
        Http::fake();

        $this->actingAs(User::factory()->owner()->create())
            ->postJson(route('ai.tanya'), ['pesan' => 'Tes'])
            ->assertStatus(503)
            ->assertJsonPath('pesan', 'Fitur AI belum diaktifkan. Isi VIKEY_API_KEY di file .env.');

        Http::assertNothingSent();
    }

    public function test_invalid_api_key_returns_friendly_error(): void
    {
        Http::fake([self::URL => Http::response(['error' => ['message' => 'Invalid key']], 401)]);

        $this->actingAs(User::factory()->owner()->create())
            ->postJson(route('ai.tanya'), ['pesan' => 'Tes'])
            ->assertStatus(503)
            ->assertJsonPath('pesan', 'API key AI tidak valid atau langganan tidak aktif.');
    }

    public function test_insight_is_cached_until_refreshed(): void
    {
        $this->fakeJawaban('**Ringkasan** penjualan stabil.');
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)->postJson(route('ai.insight'))->assertOk()
            ->assertJsonPath('html', "<p><strong>Ringkasan</strong> penjualan stabil.</p>\n");
        $this->actingAs($owner)->postJson(route('ai.insight'))->assertOk();
        Http::assertSentCount(1);

        $this->actingAs($owner)->postJson(route('ai.insight'), ['segarkan' => true])->assertOk();
        Http::assertSentCount(2);
    }

    public function test_deskripsi_menu_for_keuangan(): void
    {
        $this->fakeJawaban('"Ayam bakar bumbu kecap manis dengan sambal pedas."');

        $this->actingAs(User::factory()->keuangan()->create())
            ->postJson(route('ai.deskripsi-menu'), ['name' => 'KO Ayam Bakar', 'price' => 19000])
            ->assertOk()
            ->assertJsonPath('deskripsi', 'Ayam bakar bumbu kecap manis dengan sambal pedas.');
    }

    public function test_access_is_limited_to_owner_and_keuangan(): void
    {
        Http::fake();
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir)->get(route('ai.asisten'))->assertForbidden();
        $this->actingAs($kasir)->postJson(route('ai.tanya'), ['pesan' => 'Tes'])->assertForbidden();
        $this->actingAs($kasir)->postJson(route('ai.insight'))->assertForbidden();
        $this->actingAs($kasir)->postJson(route('ai.deskripsi-menu'), ['name' => 'X'])->assertForbidden();

        // Deskripsi menu hanya untuk keuangan (pengelola menu)
        $this->actingAs(User::factory()->owner()->create())
            ->postJson(route('ai.deskripsi-menu'), ['name' => 'X'])->assertForbidden();

        Http::assertNothingSent();
    }

    public function test_guest_cannot_use_ai(): void
    {
        Http::fake();

        $this->postJson(route('ai.tanya'), ['pesan' => 'Tes'])->assertUnauthorized();
        $this->postJson(route('ai.insight'))->assertUnauthorized();

        Http::assertNothingSent();
    }

    public function test_asisten_page_renders_for_both_roles(): void
    {
        $this->actingAs(User::factory()->owner()->create())->get(route('ai.asisten'))->assertOk()->assertSee('Tanya AI');
        $this->actingAs(User::factory()->keuangan()->create())->get(route('ai.asisten'))->assertOk()->assertSee('Tanya AI');

        config(['services.vikey.api_key' => null]);
        $this->actingAs(User::factory()->owner()->create())->get(route('ai.asisten'))->assertSee('Fitur AI belum aktif');
    }

    public function test_menu_description_is_saved_and_shown_in_pos(): void
    {
        $this->actingAs(User::factory()->keuangan()->create())->post(route('menu.store'), [
            'name' => 'Es Teh', 'code' => 'MN-1', 'quantity' => 0, 'price' => 5000,
            'description' => 'Teh manis dingin yang segar.',
        ]);

        $this->assertSame('Teh manis dingin yang segar.', Item::sole()->description);

        $this->actingAs(User::factory()->kasir()->create())
            ->get(route('kasir.pos'))
            ->assertSee('Teh manis dingin yang segar.');
    }

    public function test_context_contains_correct_aggregates(): void
    {
        $this->travelTo('2026-10-27 10:00:00');
        $cash = Source::factory()->create(['name' => 'CASH']);
        $listrik = Bill::factory()->create(['name' => 'Listrik']);
        $ayam = Item::factory()->create(['name' => 'Ayam Bakar', 'price' => 20000]);
        Item::factory()->create(['name' => 'Es Teh', 'price' => 5000]);

        $penjualan = Income::factory()->payment()->create(['amount' => 40000, 'date' => '2026-10-27', 'source_id' => $cash->id]);
        IncomeDetail::create(['income_id' => $penjualan->id, 'item_id' => $ayam->id, 'quantity' => 2, 'subtotal' => 40000]);
        Income::factory()->create(['amount' => 100000, 'date' => '2026-10-05', 'source_id' => $cash->id]);
        Income::factory()->create(['amount' => 70000, 'date' => '2026-09-15', 'source_id' => $cash->id]);
        Expense::factory()->create(['amount' => 30000, 'date' => '2026-10-10', 'bill_id' => $listrik->id, 'source_id' => $cash->id]);
        Income::factory()->create(['amount' => 999999, 'date' => '2026-10-27'])->delete();

        $data = app(KonteksKeuangan::class)->ringkasan();

        $this->assertSame(40000, $data['periode']['hari_ini']['total_pemasukan']);
        $this->assertSame(1, $data['periode']['hari_ini']['jumlah_transaksi_penjualan']);
        $this->assertSame(140000, $data['periode']['bulan_ini_sampai_hari_ini']['total_pemasukan']);
        $this->assertSame(110000, $data['periode']['bulan_ini_sampai_hari_ini']['laba_rugi']);
        $this->assertSame(70000, $data['periode']['bulan_lalu']['total_pemasukan']);
        $this->assertSame(['total' => 140000, 'transaksi' => 2], $data['pemasukan_per_sumber']['bulan_ini']['CASH']);
        $this->assertSame(['Listrik' => 30000], $data['pengeluaran_per_kategori']['bulan_ini']);
        $this->assertSame([['menu' => 'Ayam Bakar', 'harga' => 20000, 'porsi' => 2, 'omzet' => 40000]], $data['menu_terjual']['bulan_ini']);
        $this->assertSame(['Es Teh'], $data['menu_belum_terjual_bulan_ini']);
        $this->assertSame('Ayam Bakar', collect($data['daftar_menu_dan_harga'])->last()['menu']);
        $this->assertCount(2, $data['daftar_menu_dan_harga']);
        $this->assertSame(40000, $data['penjualan_harian_30_hari']['2026-10-27']['penjualan']);
        $this->assertCount(30, $data['penjualan_harian_30_hari']);
    }
}
