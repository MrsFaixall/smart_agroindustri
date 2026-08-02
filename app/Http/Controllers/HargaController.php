<?php

namespace App\Http\Controllers;

use App\Models\Harga;
use App\Models\HargaPasar;
use App\Models\JenisKentang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class HargaController extends Controller
{
    public function index()
    {
        // Harga Petani (user login)
        $prices = Harga::with('jenisKentang')
            ->where('user_id', Auth::id())
            ->latest('updated_at')
            ->get();
            
        // Harga Pasar (Global/Koperasi)
        $hargaPasars = HargaPasar::with('jenisKentang')
            ->get()
            ->keyBy('jenis_kentang_id');

        $summary = [
            'rata_rata' => $prices->avg('harga') ?? 0,
            'tertinggi' => $prices->sortByDesc('harga')->first(),
            'terendah' => $prices->sortBy('harga')->first(),
            'total' => $prices->count(),
        ];

        return view('petani.atur-harga.index', compact('prices', 'summary', 'hargaPasars'));
    }

    public function create()
    {
        $jenisKentangs = JenisKentang::all();
        
        $hargaPasars = HargaPasar::get()->keyBy('jenis_kentang_id');
            
        return view('petani.atur-harga.create', compact('jenisKentangs', 'hargaPasars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kentang_id' => [
                'required', 
                'exists:jenis_kentangs,id', 
                Rule::unique('hargas', 'jenis_kentang_id')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
            'harga' => 'required|numeric',
        ]);

        $data['user_id'] = Auth::id();
        Harga::create($data);

        return redirect('/atur-harga')->with('success', 'Harga kentang Anda berhasil disimpan.');
    }

    public function show(string $id)
    {
        return redirect('/atur-harga');
    }

    public function edit(string $id)
    {
        $price = Harga::where('user_id', Auth::id())->findOrFail($id);
        $jenisKentangs = JenisKentang::all();
        
        $hargaPasar = HargaPasar::where('jenis_kentang_id', $price->jenis_kentang_id)->first();
            
        return view('petani.atur-harga.edit', compact('price', 'jenisKentangs', 'hargaPasar'));
    }

    public function update(Request $request, string $id)
    {
        $price = Harga::where('user_id', Auth::id())->findOrFail($id);
        
        $data = $request->validate([
            'jenis_kentang_id' => [
                'required',
                'exists:jenis_kentangs,id',
                Rule::unique('hargas', 'jenis_kentang_id')->where(function ($query) use ($price) {
                    return $query->where('user_id', Auth::id());
                })->ignore($price->id),
            ],
            'harga' => 'required|numeric',
        ]);

        $price->update($data);

        return redirect('/atur-harga')->with('success', 'Harga kentang Anda berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Harga::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect('/atur-harga')->with('success', 'Harga kentang berhasil dihapus.');
    }
}
