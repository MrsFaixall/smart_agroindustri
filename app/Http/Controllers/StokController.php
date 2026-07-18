<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $stoks = Stok::with(['gudang', 'jenisKentang'])->get();
        $totalMax = Gudang::sum('kapasitas_max');
        $totalStok = $stoks->sum('jumlah_stok');
        $utilitasGudang = $totalMax > 0 ? round(($totalStok / $totalMax) * 100) : 0;

        return view('petani.stok.index', compact('stoks', 'utilitasGudang'));
    }

    public function create()
    {
        $gudangs = Gudang::all();
        $jenisKentangs = JenisKentang::all();
        return view('petani.stok.create', compact('gudangs', 'jenisKentangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_stok' => 'required|numeric',
            'grade' => 'required|string|in:A,B,C',
        ]);

        Stok::create($data);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return redirect()->route('stok.index');
    }

    public function edit(string $id)
    {
        $stok = Stok::findOrFail($id);
        $gudangs = Gudang::all();
        $jenisKentangs = JenisKentang::all();

        return view('petani.stok.edit', compact('stok', 'gudangs', 'jenisKentangs'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_stok' => 'required|numeric',
            'grade' => 'required|string|in:A,B,C',
        ]);

        Stok::findOrFail($id)->update($data);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Stok::findOrFail($id)->delete();
        return redirect()->route('stok.index')->with('success', 'Stok berhasil dihapus.');
    }
}
