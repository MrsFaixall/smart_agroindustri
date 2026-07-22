<?php

namespace App\Http\Controllers;

use App\Models\JenisKentang;
use Illuminate\Http\Request;

class JenisKentangController extends Controller
{
    public function index()
    {
        $data = JenisKentang::all();
        return view('admin.jenis_kentang.index', compact('data'));
    }

    public function create()
    {
        return view('admin.jenis_kentang.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:100',
            'kualitas' => 'required|string|max:100',
        ]);

        JenisKentang::create($request->all());
        return redirect()->route('admin.jenis_kentang.index')->with('success', 'Data tersimpan!');
    }

    public function edit($id) {
        $item = JenisKentang::findOrFail($id);
        return view('admin.jenis_kentang.edit', compact('item'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'satuan' => 'required|string|max:100',
            'kualitas' => 'required|string|max:100',
        ]);

        JenisKentang::findOrFail($id)->update($request->all());
        return redirect()->route('admin.jenis_kentang.index')->with('success', 'Data diupdate!');
    }

    public function destroy($id) {
        JenisKentang::findOrFail($id)->delete();
        return redirect()->route('admin.jenis_kentang.index')->with('success', 'Data dihapus!');
    }
}