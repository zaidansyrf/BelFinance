<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Source;

class UangMasukController extends Controller
{
    public function index()
    {
        $income = Income::with(['source'])->get();
        $sources = Source::all();
        return view('keuangan-uang-masuk', compact('income', 'sources'));
    }
    
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'source_id' => 'required|exists:sources,id',
            'date' => 'required|date',
            'amount' => 'required|integer',
            'description' => 'nullable|string'
        ]);

        $income = Income::create([
            'name' => $request->name,
            'source_id' => $request->source_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

}
