<?php

namespace App\Http\Controllers;

use App\Models\Bbm;
use Illuminate\Http\Request;

class BbmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Bbm::all();
        return view('admin.bbm.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bbm.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bbm' => 'required|string|max:255',
            'jumlah_liter' => 'required|numeric',
            'km' => 'required|numeric',
            'harga' => 'required|numeric',
        ]);

        Bbm::create($request->all());

        return redirect()->route('admin.bbm.index')->with('success', 'Data BBM tersimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.bbm.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Bbm::findOrFail($id);
        return view('admin.bbm.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_bbm' => 'required|string|max:255',
            'jumlah_liter' => 'required|numeric',
            'km' => 'required|numeric',
            'harga' => 'required|numeric',
        ]);

        Bbm::findOrFail($id)->update($request->all());

        return redirect()->route('admin.bbm.index')->with('success', 'Data BBM diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Bbm::findOrFail($id)->delete();
        return redirect()->route('admin.bbm.index')->with('success', 'Data BBM dihapus!');
    }
}
