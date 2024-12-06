<?php

namespace App\Http\Controllers\admin;

use App\Models\Menu;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class MenuController extends Controller
{
  // Display the list of menus
  public function index()
  {
      // Retrieve all menu items from the database
      $menus = Menu::all();
      return view('menu.index', compact('menus'));
  }

  public function create()
  {
      return view('menu.create');
  }

  // Store a new menu
  public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request data
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'harga' => 'required|integer|min:1',
        ]);

        // Create a new menu item
        Menu::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
        ]);

        // Redirect back with a success message
        return redirect()->route('menu.index')->with('success', 'Menu item added successfully!');
    }

  // Show the form for editing the menu
  public function edit(Menu $menu)
  {
      return response()->json($menu);
  }

  // Update the menu
  public function update(Request $request, Menu $menu)
  {
      $validated = $request->validate([
          'nama' => 'required|string|max:255',
          'jumlah' => 'required|integer|min:1',
          'harga' => 'required|numeric|min:0',
      ]);

      // Update the menu
      $menu->update([
          'nama' => $validated['nama'],
          'jumlah' => $validated['jumlah'],
          'harga' => $validated['harga'],
      ]);

      return redirect()->route('menu.index')->with('success', 'Menu updated successfully.');
  }

  // Delete the menu
  public function destroy(Menu $menu)
  {
      $menu->delete();
      return redirect()->route('menu.index')->with('success', 'Menu deleted successfully.');
  }
}

