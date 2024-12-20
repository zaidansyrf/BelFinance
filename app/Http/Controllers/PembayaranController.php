<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Income;
use App\Models\Source;
use App\Models\IncomeDetail;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(){
        return view('keuangan-pembayaran');
    }
    public function store(Request $request)
{
    dd($request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'source_id' => 'required|exists:sources,id',
        'items' => 'required|array',
    ]);

    $selectedItems = $request->items ?? [];
    $totalAmount = 0;
    $items = [];

    foreach ($selectedItems as $item) {
        if ($item['quantity'] > 0) {
            $itemData = Item::find($item['item_id']);
            if ($itemData) {
                $subtotal = $itemData->price * $item['quantity'];
                $totalAmount += $subtotal;

                $items[] = [
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }
    }

    $income = Income::create([
        'name' => $request->name,
        'amount' => $totalAmount,
        'date' => now(),
        'description' => 'Pesanan baru',
    ]);
    
    foreach ($items as $detail) {
        IncomeDetail::create([
            'income_id' => $income->id, 
            'item_id' => $detail['item_id'],
            'quantity' => $detail['quantity'],
            'subtotal' => $detail['subtotal'],
        ]);
    }

    return redirect()->route('pembayaran.view')->with('success', 'Pesanan berhasil disimpan!');
}
    public function create(Request $request)
    {
        $selectedItems = $request->items ?? [];
        $allItems = Item::all(); // Fetch all items from the 'items' table
        $allSources = Source::all(); // Fetch all sources from the 'sources' table
        
        return view('keuangan-create-pembayaran', compact('allItems', 'allSources'));
        
}

}