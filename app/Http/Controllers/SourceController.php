<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sources = Source::all();
        return view('kategori.source.index', compact('sources'));
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
            'name' => 'required|max:255',
        ]);
        Source::create($request->all());
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil ditambahkan.');
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
        $source = Source::findOrFail($id);
        

        return view('keuangan-source-edit', compact('source'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $source = Source::findOrFail($id);
        $source->update($request->all());
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Source::findOrFail($id)->delete();
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil dihapus.');
    }
}
