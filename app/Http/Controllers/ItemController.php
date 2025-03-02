<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default 10 item per halaman
        $search = $request->input('search'); // Ambil input pencarian
    
        // Query pencarian + pagination
        $items = Item::where('name', 'like', "%{$search}%")
                     ->orWhere('price', 'like', "%{$search}%")
                     ->paginate($perPage)
                     ->appends(['perPage' => $perPage, 'search' => $search]); // Menyimpan filter di pagination
    
        return view('item.index', compact('items', 'perPage', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:1',
            'code' => 'required|string|max:255|unique:items,code',
        ], [
            'code.unique' => 'Kode sudah digunakan, silakan gunakan kode lain.',
        ]);
        try {
            // Create a new item
            Item::create([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'code' => $request->code
            ]);

            // Redirect back with a success message
            return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan menu');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Item::destroy($id);

        // Redirect back with a success message
        // Kembali ke halaman admin/keuangan/menu
        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus');
    }
    public function search(Request $request)
{
    $query = $request->input('search');
    $items = Item::where('name', 'like', '%' . $query . '%')->get();

    return view('menu.index', compact('items'));
}

}

