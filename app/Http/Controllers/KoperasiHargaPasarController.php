<?php

namespace App\Http\Controllers;

use App\Models\HargaPasar;
use App\Models\JenisKentang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KoperasiHargaPasarController extends Controller
{
    public function index()
    {
        $prices = HargaPasar::with('jenisKentang')->latest('updated_at')->get();
        $summary = [
            'rata_rata' => $prices->avg('harga') ?? 0,
            'tertinggi' => $prices->sortByDesc('harga')->first(),
            'terendah' => $prices->sortBy('harga')->first(),
            'total' => $prices->count(),
        ];

        return view('koperasi.atur-harga-pasar.index', compact('prices', 'summary'));
    }

    public function create()
    {
        $jenisKentangs = JenisKentang::all();
        return view('koperasi.atur-harga-pasar.create', compact('jenisKentangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kentang_id' => ['required', 'exists:jenis_kentangs,id', Rule::unique('harga_pasars', 'jenis_kentang_id')],
            'harga' => 'required|numeric|min:0',
        ]);

        HargaPasar::create($data);

        return redirect()->route('koperasi.atur-harga-pasar.index')->with('success', 'Harga pasar berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $price = HargaPasar::findOrFail($id);
        $jenisKentangs = JenisKentang::all();
        return view('koperasi.atur-harga-pasar.edit', compact('price', 'jenisKentangs'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'jenis_kentang_id' => [
                'required',
                'exists:jenis_kentangs,id',
                Rule::unique('harga_pasars', 'jenis_kentang_id')->ignore($id),
            ],
            'harga' => 'required|numeric|min:0',
        ]);

        HargaPasar::findOrFail($id)->update($data);

        return redirect()->route('koperasi.atur-harga-pasar.index')->with('success', 'Harga pasar berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        HargaPasar::findOrFail($id)->delete();
        return redirect()->route('koperasi.atur-harga-pasar.index')->with('success', 'Harga pasar berhasil dihapus.');
    }
}
