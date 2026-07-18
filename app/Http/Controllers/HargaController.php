<?php

namespace App\Http\Controllers;

use App\Models\Harga;
use App\Models\JenisKentang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HargaController extends Controller
{
    public function index()
    {
        $prices = Harga::with('jenisKentang')->latest('updated_at')->get();
        $summary = [
            'rata_rata' => $prices->avg('harga') ?? 0,
            'tertinggi' => $prices->sortByDesc('harga')->first(),
            'terendah' => $prices->sortBy('harga')->first(),
            'total' => $prices->count(),
        ];

        return view('petani.atur-harga.index', compact('prices', 'summary'));
    }

    public function create()
    {
        $jenisKentangs = JenisKentang::all();
        return view('petani.atur-harga.create', compact('jenisKentangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kentang_id' => ['required', 'exists:jenis_kentangs,id', Rule::unique('hargas', 'jenis_kentang_id')],
            'harga' => 'required|numeric',
        ]);

        Harga::create($data);

        return redirect('/atur-harga')->with('success', 'Harga kentang berhasil disimpan.');
    }

    public function show(string $id)
    {
        return redirect('/atur-harga');
    }

    public function edit(string $id)
    {
        $price = Harga::findOrFail($id);
        $jenisKentangs = JenisKentang::all();
        return view('petani.atur-harga.edit', compact('price', 'jenisKentangs'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'jenis_kentang_id' => [
                'required',
                'exists:jenis_kentangs,id',
                Rule::unique('hargas', 'jenis_kentang_id')->ignore($id),
            ],
            'harga' => 'required|numeric',
        ]);

        Harga::findOrFail($id)->update($data);

        return redirect('/atur-harga')->with('success', 'Harga kentang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Harga::findOrFail($id)->delete();
        return redirect('/atur-harga')->with('success', 'Harga kentang berhasil dihapus.');
    }
}
