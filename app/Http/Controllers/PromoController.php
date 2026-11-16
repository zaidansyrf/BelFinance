<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemPromo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    /**
     * Tampilkan halaman kelola promo menu merchant & checklist menu.
     */
    public function index()
    {
        $promos = ItemPromo::with(['items'])->latest()->get();
        $items = Item::with(['promo', 'promos'])->orderBy('name')->get();

        return view('promo.item', compact('promos', 'items'));
    }

    /**
     * Simpan promo menu merchant baru dan tautkan ke menu-menu yang dicentang.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'discount_type'  => 'required|in:percent,nominal',
            'discount_value' => 'required|integer|min:1',
            'max_discount'   => 'nullable|integer|min:1',
            'channel_scope'  => 'required|in:all,online_only,offline_only',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'item_ids'       => 'nullable|array',
            'item_ids.*'     => 'exists:items,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $promo = ItemPromo::create([
                'name'           => $validated['name'],
                'discount_type'  => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'max_discount'   => $request->filled('max_discount') ? (int) $validated['max_discount'] : null,
                'channel_scope'  => $validated['channel_scope'],
                'start_date'     => $validated['start_date'] ?? null,
                'end_date'       => $validated['end_date'] ?? null,
                'is_active'      => true,
            ]);

            if (! empty($validated['item_ids'])) {
                $promo->items()->sync($validated['item_ids']);
                Item::whereIn('id', $validated['item_ids'])->update(['item_promo_id' => $promo->id]);
            }
        });

        return redirect()->route('promo.item.index')->with('success', 'Promo menu berhasil dibuat dan ditautkan ke menu.');
    }

    /**
     * Update promo menu merchant dan sinkronisasi menu yang dicentang.
     */
    public function update(Request $request, ItemPromo $promo)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'discount_type'  => 'required|in:percent,nominal',
            'discount_value' => 'required|integer|min:1',
            'max_discount'   => 'nullable|integer|min:1',
            'channel_scope'  => 'required|in:all,online_only,offline_only',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'item_ids'       => 'nullable|array',
            'item_ids.*'     => 'exists:items,id',
        ]);

        DB::transaction(function () use ($promo, $validated, $request) {
            $promo->update([
                'name'           => $validated['name'],
                'discount_type'  => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'max_discount'   => $request->filled('max_discount') ? (int) $validated['max_discount'] : null,
                'channel_scope'  => $validated['channel_scope'],
                'start_date'     => $validated['start_date'] ?? null,
                'end_date'       => $validated['end_date'] ?? null,
            ]);

            $newItemIds = $validated['item_ids'] ?? [];
            $promo->items()->sync($newItemIds);

            // Sinkronisasi kolom fallback item_promo_id
            Item::where('item_promo_id', $promo->id)
                ->whereNotIn('id', $newItemIds)
                ->update(['item_promo_id' => null]);

            if (! empty($newItemIds)) {
                Item::whereIn('id', $newItemIds)->update(['item_promo_id' => $promo->id]);
            }
        });

        return redirect()->route('promo.item.index')->with('success', 'Promo menu berhasil diperbarui.');
    }

    /**
     * Toggle status aktif promo menu.
     */
    public function toggle(ItemPromo $promo)
    {
        $promo->update(['is_active' => ! $promo->is_active]);
        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('promo.item.index')->with('success', "Promo menu {$promo->name} berhasil {$status}.");
    }

    /**
     * Hapus promo menu dan lepaskan relasi di items.
     */
    public function destroy(ItemPromo $promo)
    {
        DB::transaction(function () use ($promo) {
            $promo->items()->detach();
            Item::where('item_promo_id', $promo->id)->update(['item_promo_id' => null]);
            $promo->delete();
        });

        return redirect()->route('promo.item.index')->with('success', 'Promo menu berhasil dihapus.');
    }
}
