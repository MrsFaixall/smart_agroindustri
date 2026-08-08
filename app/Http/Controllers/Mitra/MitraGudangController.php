<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;

use App\Models\Gudang;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class MitraGudangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Gudang::where('jenis_gudang', 'mitra')->with(['stoks', 'user']);
        
        if ($user->role === 'mitra') {
            $query->where('user_id', $user->id);
        }
        
        $gudangs = $query->get();
        $mapGudangs = $gudangs
            ->filter(fn (Gudang $gudang) => is_numeric($gudang->latitude) && is_numeric($gudang->longitude))
            ->map(fn (Gudang $gudang) => [
                'nama_gudang' => $gudang->nama_gudang,
                'alamat' => $gudang->alamat,
                'wilayah' => collect([$gudang->kelurahan, $gudang->kecamatan, $gudang->kota, $gudang->provinsi])
                    ->filter()
                    ->implode(', '),
                'latitude' => (float) $gudang->latitude,
                'longitude' => (float) $gudang->longitude,
                'kapasitas_terpakai' => $gudang->kapasitas_terpakai,
                'kapasitas_max' => $gudang->kapasitas_max,
            ])
            ->values();
        
        $totalGudang = $gudangs->count();
        $kapasitasGlobal = $gudangs->sum('kapasitas_max');
        $gudangAktif = $gudangs->filter(fn (Gudang $gudang) => $gudang->persentase_kapasitas < 90)->count();
        
        $gudangPenuh = 0;
        foreach($gudangs as $g) {
            if ($g->persentase_kapasitas >= 90) {
                $gudangPenuh++;
            }
        }

        return view('mitra.gudang.index', compact('gudangs', 'mapGudangs', 'totalGudang', 'kapasitasGlobal', 'gudangAktif', 'gudangPenuh'));
    }

    public function create()
    {
        return view('mitra.gudang.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $data['jenis_gudang'] = 'mitra';
        $data['user_id'] = Auth::id();
        Gudang::create($data);

        return redirect()->route('mitra-gudang.index')->with('success', 'Gudang Mitra berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('mitra.gudang.edit', compact('gudang'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        Gudang::findOrFail($id)->update($data);

        return redirect()->route('mitra-gudang.index')->with('success', 'Gudang Mitra berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $gudang = Gudang::findOrFail($id);

        DB::transaction(function () use ($gudang) {
            $gudang->stoks()->delete();
            $gudang->delete();
        });

        return redirect()->route('mitra-gudang.index')->with('success', 'Gudang Mitra berhasil dihapus.');
    }
}
