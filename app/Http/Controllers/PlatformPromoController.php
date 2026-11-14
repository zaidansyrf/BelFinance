<?php

namespace App\Http\Controllers;

use App\Models\PlatformPromo;
use App\Models\Source;
use Illuminate\Http\Request;

class PlatformPromoController extends Controller
{
    /**
     * Tampilkan halaman kelola preset voucher aplikasi & konfigurasi komisi platform.
     */
    public function index()
    {
        $sources = Source::with(['platformPromos'])->orderBy('name')->get();
        $promos = PlatformPromo::with('source')->latest()->get();

        return view('promo.platform', compact('sources', 'promos'));
    }

    /**
     * Simpan preset voucher aplikasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_id'              => 'required|exists:sources,id',
            'name'                   => 'required|string|max:150',
            'discount_type'          => 'required|in:percent,nominal',
            'discount_value'         => 'required|integer|min:1',
            'max_discount'           => 'nullable|integer|min:1',
            'merchant_share_percent' => 'required|numeric|min:0|max:100',
        ]);

        PlatformPromo::create([
            'source_id'              => $validated['source_id'],
            'name'                   => $validated['name'],
            'discount_type'          => $validated['discount_type'],
            'discount_value'         => $validated['discount_value'],
            'max_discount'           => $request->filled('max_discount') ? (int) $validated['max_discount'] : null,
            'merchant_share_percent' => $validated['merchant_share_percent'],
            'is_active'              => true,
        ]);

        return redirect()->route('promo.platform.index')->with('success', 'Preset voucher platform berhasil ditambahkan.');
    }

    /**
     * Update preset voucher platform.
     */
    public function update(Request $request, PlatformPromo $platformPromo)
    {
        $validated = $request->validate([
            'source_id'              => 'required|exists:sources,id',
            'name'                   => 'required|string|max:150',
            'discount_type'          => 'required|in:percent,nominal',
            'discount_value'         => 'required|integer|min:1',
            'max_discount'           => 'nullable|integer|min:1',
            'merchant_share_percent' => 'required|numeric|min:0|max:100',
        ]);

        $platformPromo->update([
            'source_id'              => $validated['source_id'],
            'name'                   => $validated['name'],
            'discount_type'          => $validated['discount_type'],
            'discount_value'         => $validated['discount_value'],
            'max_discount'           => $request->filled('max_discount') ? (int) $validated['max_discount'] : null,
            'merchant_share_percent' => $validated['merchant_share_percent'],
        ]);

        return redirect()->route('promo.platform.index')->with('success', 'Preset voucher platform berhasil diperbarui.');
    }

    /**
     * Toggle status aktif preset voucher platform.
     */
    public function toggle(PlatformPromo $platformPromo)
    {
        $platformPromo->update(['is_active' => ! $platformPromo->is_active]);
        $status = $platformPromo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('promo.platform.index')->with('success', "Preset voucher {$platformPromo->name} berhasil {$status}.");
    }

    /**
     * Hapus preset voucher platform.
     */
    public function destroy(PlatformPromo $platformPromo)
    {
        $platformPromo->delete();

        return redirect()->route('promo.platform.index')->with('success', 'Preset voucher berhasil dihapus.');
    }

    /**
     * Perbarui konfigurasi platform online & persentase komisi standar pada Source.
     */
    public function updateSource(Request $request, Source $source)
    {
        $validated = $request->validate([
            'is_online'                  => 'nullable|boolean',
            'default_commission_percent' => 'required|numeric|min:0|max:100',
        ]);

        $source->update([
            'is_online'                  => $request->boolean('is_online'),
            'default_commission_percent' => $validated['default_commission_percent'],
        ]);

        return redirect()->route('promo.platform.index')->with('success', "Konfigurasi saluran {$source->name} berhasil diperbarui.");
    }
}
