<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Gambar menu opsional: jpg/png/webp, maksimal 2 MB
    private const IMAGE_RULES = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';

    private const IMAGE_MESSAGES = [
        'image.image' => 'File gambar tidak valid.',
        'image.mimes' => 'Gambar harus berformat JPG, PNG, atau WEBP.',
        'image.max' => 'Ukuran gambar maksimal 2 MB.',
    ];

    // method untuk menampilkan data
    public function index()
    {
        $items = DB::table('items')->orderBy('code', 'asc')->paginate(10);

        // Pie chart data
        $topItems = Item::orderByDesc('quantity')->take(5)->get();
        $chartData = [
            'labels' => $topItems->pluck('name'),
            'quantities' => $topItems->pluck('quantity'),
        ];

        $totalSold = Item::sum('quantity');
        $topSellingMenu = Item::orderByDesc('quantity')->first();

        return view('menu.search', compact('items', 'chartData', 'totalSold', 'topSellingMenu'));
    }

    // method pencarian
    public function search(Request $request)
    {
        $search = $request->input('search');

        $items = Item::query()
            ->where(function ($query) use ($search) {
                $query->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('price', 'LIKE', "%{$search}%");
            })
            ->orderBy('code', 'asc')
            ->paginate(10);

        $topItems = Item::orderByDesc('quantity')->take(5)->get();
        $chartData = [
            'labels' => $topItems->pluck('name'),
            'quantities' => $topItems->pluck('quantity'),
        ];

        $totalSold = Item::sum('quantity');
        $topSellingMenu = Item::orderByDesc('quantity')->first();
        $totalRevenue = Item::sum(DB::raw('quantity * price'));

        return view('item.index', compact('items', 'search', 'chartData', 'totalSold', 'topSellingMenu', 'totalRevenue'));
    }

    public function create()
    {
        //
    }

    // method menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:0',
            'stock' => 'nullable|integer|min:0|max:1000000',
            'price' => 'required|numeric|min:1',
            'code' => 'nullable|string|max:255|unique:items,code',
            'image' => self::IMAGE_RULES,
        ], [
            'code.unique' => 'Kode sudah digunakan, silakan gunakan kode lain.',
        ] + self::IMAGE_MESSAGES);

        try {
            $code = $request->code;
            if (empty($code)) {
                $words = explode(' ', strtoupper($request->name));
                $prefix = '';

                if (count($words) > 0) {
                    $prefix = $words[0];

                    if (strlen($prefix) < 3 && isset($words[1])) {
                        $prefix .= substr($words[1], 0, 3 - strlen($prefix));
                    }

                    $prefix = substr($prefix, 0, 3);
                }

                $lastItem = Item::where('code', 'LIKE', $prefix . '%')
                                ->orderBy('code', 'desc')
                                ->first();

                $nextNumber = 1;
                if ($lastItem) {
                    $lastNumber = (int)substr($lastItem->code, strlen($prefix));
                    $nextNumber = $lastNumber + 1;
                }
                // generate code
                $code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            Item::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'stock' => $request->filled('stock') ? (int) $request->stock : null,
                'code' => $code,
                'image' => $request->file('image')?->store('menu', 'public'),
            ]);

            return redirect()->route('menu.search')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan menu');
        }
    }

    public function show(string $id)
    {
        // tampilkan detail item berdasarkan ID
        $item = Item::findOrFail($id);
        return view('item.show', compact('item'));
    }

    // method edit
    public function edit(string $id)
    {
        // tampilkan form edit item berdasarkan ID
        $item = Item::findOrFail($id);

        return view('item.edit-menu', compact('item'));
    }

    // method update
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:0',
            'stock' => 'nullable|integer|min:0|max:1000000',
            'price' => 'required|numeric|min:1',
            'code' => 'required|string|max:255|unique:items,code,'.$id,
            'image' => self::IMAGE_RULES,
            'remove_image' => 'nullable|boolean',
        ], [
            'code.unique' => 'Kode sudah digunakan, silakan gunakan kode lain.',
        ] + self::IMAGE_MESSAGES);

        try {
            // Update item
            $item = Item::findOrFail($id);
            $oldImage = $item->image;

            $image = $oldImage;
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('menu', 'public');
            } elseif ($request->boolean('remove_image')) {
                $image = null;
            }

            $item->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'stock' => $request->filled('stock') ? (int) $request->stock : null,
                'code' => $request->code,
                'image' => $image,
            ]);

            // Hapus file gambar lama setelah data berhasil diperbarui
            if ($oldImage && $oldImage !== $image) {
                Storage::disk('public')->delete($oldImage);
            }

            return redirect()->route('menu.search')->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui menu');
        }
    }

    // method hapus
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);

        // Menu yang sudah pernah terjual tidak bisa dihapus (dipakai di riwayat transaksi)
        if ($item->incomeDetails()->exists()) {
            return redirect()->route('menu.search')
                ->with('error', "Menu {$item->name} sudah pernah terjual sehingga tidak bisa dihapus.");
        }

        $item->delete();
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        return redirect()->route('menu.search')->with('success', 'Menu berhasil dihapus');
    }
}
