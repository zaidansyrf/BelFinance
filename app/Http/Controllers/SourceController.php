<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SourceController extends Controller
{
    public function index()
    {
        // $sources = Source::all();
        return view('kategori.source.index', [
            'sources' => DB::table('sources')->paginate(5)
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        Source::create($request->all());
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $source = Source::findOrFail($id);
        

        return view('keuangan-source-edit', compact('source'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $source = Source::findOrFail($id);
        $source->update($request->all());
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        Source::findOrFail($id)->delete();
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil dihapus.');
    }
}
