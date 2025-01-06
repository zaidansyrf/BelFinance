<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      // Retrieve all item items from the database
      $items = Item::all();
      return view('item.index', compact('items'));
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
        ]);

        // Coba jika inputan tidak berupa angka
        if (!is_numeric($request->quantity)) {
            return redirect()->back()->with('error', 'Harus berupa angka');
        }

        // Create a new item item
        Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        // Redirect back with a success message
        // Kembali ke halaman admin/keuangan/menu
        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan');
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

