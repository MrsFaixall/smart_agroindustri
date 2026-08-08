<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use App\Models\KategoriKentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriKentangController extends Controller
{
    public function index()
    {
        $data = KategoriKentang::all();
        return view('admin.kategori_kentang.index', compact('data'));
    }

    public function create()
    {
        return view('admin.kategori_kentang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'tipe_komoditas' => 'required|in:benih,konsumsi,olahan',
        ]);

        KategoriKentang::create($request->all());
        return redirect()->route('admin.kategori_kentang.index')->with('success', 'Kategori Kentang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = KategoriKentang::findOrFail($id);
        return view('admin.kategori_kentang.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'tipe_komoditas' => 'required|in:benih,konsumsi,olahan',
        ]);

        KategoriKentang::findOrFail($id)->update($request->all());
        return redirect()->route('admin.kategori_kentang.index')->with('success', 'Kategori Kentang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = KategoriKentang::findOrFail($id);
                // Cannot delete if there are jenis kentangs attached
                if ($item->jenisKentangs()->count() > 0) {
                    throw new \Exception("Kategori masih digunakan oleh Jenis Kentang!");
                }
                $item->delete();
            });

            return redirect()->back()->with('success', 'Kategori Kentang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
