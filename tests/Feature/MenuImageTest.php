<?php

namespace Tests\Feature;

use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuImageTest extends TestCase
{
    use RefreshDatabase;

    private User $keuangan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->keuangan = User::factory()->keuangan()->create();
    }

    public function test_menu_can_be_created_with_image(): void
    {
        $this->actingAs($this->keuangan)->post(route('menu.store'), [
            'name' => 'Es Teh',
            'code' => 'MN-01',
            'quantity' => 0,
            'price' => 5000,
            'image' => UploadedFile::fake()->image('esteh.jpg', 400, 300),
        ])->assertRedirect(route('menu.search'));

        $item = Item::sole();
        $this->assertNotNull($item->image);
        Storage::disk('public')->assertExists($item->image);
        $this->assertStringContainsString($item->image, $item->image_url);
    }

    public function test_menu_without_image_uses_initials_placeholder(): void
    {
        $item = Item::factory()->create(['name' => 'KO Ayam Bakar']);

        $this->assertNull($item->image_url);
        $this->assertSame('AB', $item->initials);

        $this->actingAs($this->keuangan)->get(route('menu.search'))->assertOk()->assertSee('AB');
    }

    public function test_non_image_or_too_large_file_is_rejected(): void
    {
        $this->actingAs($this->keuangan)->post(route('menu.store'), [
            'name' => 'Es Jeruk', 'code' => 'MN-02', 'quantity' => 0, 'price' => 6000,
            'image' => UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('image');

        $this->actingAs($this->keuangan)->post(route('menu.store'), [
            'name' => 'Es Jeruk', 'code' => 'MN-02', 'quantity' => 0, 'price' => 6000,
            'image' => UploadedFile::fake()->image('besar.jpg')->size(3000),
        ])->assertSessionHasErrors('image');

        $this->assertSame(0, Item::count());
    }

    public function test_replacing_image_deletes_old_file(): void
    {
        $old = UploadedFile::fake()->image('lama.jpg')->store('menu', 'public');
        $item = Item::factory()->create(['image' => $old]);

        $this->actingAs($this->keuangan)->put(route('menu.update', $item->id), [
            'name' => $item->name, 'code' => $item->code, 'quantity' => 0, 'price' => $item->price,
            'image' => UploadedFile::fake()->image('baru.png'),
        ])->assertRedirect(route('menu.search'));

        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($item->fresh()->image);
    }

    public function test_image_can_be_removed(): void
    {
        $old = UploadedFile::fake()->image('lama.jpg')->store('menu', 'public');
        $item = Item::factory()->create(['image' => $old]);

        $this->actingAs($this->keuangan)->put(route('menu.update', $item->id), [
            'name' => $item->name, 'code' => $item->code, 'quantity' => 0, 'price' => $item->price,
            'remove_image' => 1,
        ]);

        $this->assertNull($item->fresh()->image);
        Storage::disk('public')->assertMissing($old);
    }

    public function test_menu_that_was_sold_cannot_be_deleted(): void
    {
        $item = Item::factory()->create();
        IncomeDetail::create([
            'income_id' => Income::factory()->payment()->create()->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'subtotal' => $item->price,
        ]);

        $this->actingAs($this->keuangan)
            ->delete(route('menu.destroy', $item->id))
            ->assertSessionHas('error');

        $this->assertModelExists($item);
    }

    public function test_unsold_menu_and_its_image_are_deleted(): void
    {
        $image = UploadedFile::fake()->image('menu.jpg')->store('menu', 'public');
        $item = Item::factory()->create(['image' => $image]);

        $this->actingAs($this->keuangan)->delete(route('menu.destroy', $item->id));

        $this->assertModelMissing($item);
        Storage::disk('public')->assertMissing($image);
    }
}
