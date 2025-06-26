<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{

    //emthod untuk menampilkan data
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



    //method pencarian
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


        public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:1',
    ]);

    try {
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
        //generate code
        $newCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT); 

        Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'code' => $newCode,
        ]);

        return redirect()->route('menu.search')->with('success', 'Menu berhasil ditambahkan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan menu');
    }
}

    public function show(string $id)
    {
        // Menampilkan detail item berdasarkan ID
        $item = Item::findOrFail($id);
        return view('item.show', compact('item'));
    }

    //method edit
    public function edit(string $id)
    {
        // Menampilkan form edit item berdasarkan ID
        $item = Item::findOrFail($id);
        return view('item.edit-menu', compact('item'));
    }

    //method update
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:1',
            'code' => 'required|string|max:255|unique:items,code,' . $id,
        ], [
            'code.unique' => 'Kode sudah digunakan, silakan gunakan kode lain.',
        ]);

        try {
            // Update item
            $item = Item::findOrFail($id);
            $item->update([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'code' => $request->code
            ]);

            // Redirect kembali dengan pesan
            return redirect()->route('menu.search')->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui menu');
        }
    }

    //method hapus
    public function destroy(string $id)
    {
        Item::destroy($id);

        // Redirect back with a success message
        // Kembali ke halaman admin/keuangan/menu
        return redirect()->route('menu.search')->with('success', 'Menu berhasil dihapus');
    }

    public function chart()
    {
        $items = Item::orderByDesc('quantity')->take(5)->get();

        $chartData = [
            'labels' => $items->pluck('name'),
            'quantities' => $items->pluck('quantity'),
        ];

        return view('item.chart', compact('chartData'));
    }

}

