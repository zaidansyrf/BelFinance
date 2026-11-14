<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SourceController extends Controller
{
    protected $fillable = [
    'name',
    'is_online',
    'default_commission_percent',
];

protected $casts = [
    'is_online' => 'boolean',
    'default_commission_percent' => 'float',
];
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
    $validated = $request->validate([
        'name'                       => 'required|string|max:255',
        'is_online'                  => 'required|boolean',
        'default_commission_percent' => 'nullable|numeric|min:0|max:100',
    ]);

    // Pastikan jika offline, komisi selalu 0
    if (! $validated['is_online']) {
        $validated['default_commission_percent'] = 0;
    }

    Source::create([
        'name'                       => $validated['name'],
        'is_online'                  => (bool) $validated['is_online'],
        'default_commission_percent' => $validated['default_commission_percent'] ?? 0,
    ]);

    return redirect()->back()->with('success', 'Sumber masuk berhasil ditambahkan.');
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
        $validated = $request->validate([
            'name'                       => 'required|string|max:255',
            'is_online'                  => 'required|boolean',
            'default_commission_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if (! $validated['is_online']) {
            $validated['default_commission_percent'] = 0;
        }

        $source = Source::findOrFail($id);
        $source->update([
            'name'                       => $validated['name'],
            'is_online'                  => (bool) $validated['is_online'],
            'default_commission_percent' => $validated['default_commission_percent'] ?? 0,
        ]);

        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        Source::findOrFail($id)->delete();
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil dihapus.');
    }
}
