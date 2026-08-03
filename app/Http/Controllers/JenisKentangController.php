<?php

namespace App\Http\Controllers;

use App\Models\JenisKentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisKentangController extends Controller
{
    public function index()
    {
        $data = JenisKentang::all();
        return view('admin.jenis_kentang.index', compact('data'));
    }

    public function create()
    {
        $kategoris = \App\Models\KategoriKentang::all();
        return view('admin.jenis_kentang.create', compact('kategoris'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:100',
            'kualitas' => 'required|string|max:100',
            'kategori_kentang_id' => 'required|exists:kategori_kentangs,id',
        ]);

        JenisKentang::create($request->all());
        return redirect()->route('admin.jenis_kentang.index')->with('success', 'Data tersimpan!');
    }

    public function edit($id) {
        $item = JenisKentang::findOrFail($id);
        $kategoris = \App\Models\KategoriKentang::all();
        return view('admin.jenis_kentang.edit', compact('item', 'kategoris'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:100',
            'kualitas' => 'required|string|max:100',
            'kategori_kentang_id' => 'required|exists:kategori_kentangs,id',
        ]);

        JenisKentang::findOrFail($id)->update($request->all());
        return redirect()->route('admin.jenis_kentang.index')->with('success', 'Data diupdate!');
    }

    public function destroy($id) {
        try {
            DB::transaction(function () use ($id) {
                $item = JenisKentang::findOrFail($id);
                // Clean up dependent child records to prevent foreign key constraint violation (Error 1451)
                \App\Models\Harga::where('jenis_kentang_id', $id)->delete();
                \App\Models\Stok::where('jenis_kentang_id', $id)->delete();
                \App\Models\Panen::where('jenis_kentang_id', $id)->delete();
                \App\Models\Pembelian::where('jenis_kentang_id', $id)->delete();
                $item->delete();
            });

            return redirect()->back()->with('success', 'Data jenis kentang beserta seluruh data terkait berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data jenis kentang: ' . $e->getMessage());
        }
    }
}