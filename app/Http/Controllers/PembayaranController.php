<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Income;
use App\Models\IncomeDetail;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function view(){
        return view('keuangan-pembayaran');
    }
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'items' => 'required|array',
    ]);

    $totalAmount = 0;
    $items = [];

    foreach ($request->items as $item) {
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

    return redirect()->route('income.index')->with('success', 'Pesanan berhasil disimpan!');
}

}
