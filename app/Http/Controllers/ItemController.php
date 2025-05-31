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
      return view('item.index', [
        'items' => DB::table('items')
          ->orderBy('code', 'asc')
          ->paginate(10)
      ]);

    }

    //method pencarian
    public function search(Request $request)
    {
        $search = $request->input('search');

        $items = Item::query()
            ->where(function ($query) use ($search) {
                $query->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('price', 'LIKE', "%{$search}%"); // Tambahkan pencarian berdasarkan harga
            })
            ->orderBy('code', 'asc')
            ->paginate(10);

        return view('item.index', compact('items', 'search'));
    }


    public function create()
    {
        //
    }

    //method menyimpan data ke database
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
            // membuat item baru
            Item::create([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'code' => $request->code
            ]);

            // Redirect kembali dengan pesan
            return redirect()->route('item.index')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle errors
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
        return redirect()->route('item.index')->with('success', 'Menu berhasil dihapus');
    }


}

