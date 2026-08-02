<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Gudang::where('jenis_gudang', 'petani')->with(['stoks', 'panens', 'user']);
        
        if ($user->role === 'petani') {
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
        // Tabel gudang belum memiliki kolom status; status aktif di UI berarti
        // kapasitasnya belum memasuki batas penuh.
        $gudangAktif = $gudangs->filter(fn (Gudang $gudang) => $gudang->persentase_kapasitas < 90)->count();
        
        // hitung gudang penuh (>= 90%)
        $gudangPenuh = 0;
        foreach($gudangs as $g) {
            if ($g->persentase_kapasitas >= 90) {
                $gudangPenuh++;
            }
        }

        return view('petani.gudang.index', compact('gudangs', 'mapGudangs', 'totalGudang', 'kapasitasGlobal', 'gudangAktif', 'gudangPenuh'));
    }

    /**
     * Proxy data wilayah so the form is not affected by the browser's CORS policy.
     */
    public function wilayah(string $level, ?string $parentId = null)
    {
        $endpoints = [
            'provinsi' => 'provinces.json',
            'kota' => $parentId ? "regencies/{$parentId}.json" : null,
            'kecamatan' => $parentId ? "districts/{$parentId}.json" : null,
            'kelurahan' => $parentId ? "villages/{$parentId}.json" : null,
        ];

        abort_unless(array_key_exists($level, $endpoints) && $endpoints[$level], 404);

        try {
            $items = Cache::remember("wilayah-indonesia.{$level}.{$parentId}", now()->addDays(7), function () use ($endpoints, $level) {
                return Http::acceptJson()
                    ->timeout(10)
                    ->get('https://www.emsifa.com/api-wilayah-indonesia/api/'.$endpoints[$level])
                    ->throw()
                    ->json();
            });
        } catch (ConnectionException|\Illuminate\Http\Client\RequestException $exception) {
            return response()->json([
                'message' => 'Data wilayah sementara tidak dapat dimuat. Silakan coba lagi.',
            ], 502);
        }

        return response()->json(collect($items)->map(fn ($item) => [
            'id' => $item['id'],
            'name' => $item['name'],
        ])->values());
    }

    /** Find a map coordinate from the selected Indonesian administrative region. */
    public function cariLokasi(Request $request)
    {
        $wilayah = $request->validate([
            'wilayah' => ['required', 'string', 'max:500'],
        ])['wilayah'];

        try {
            $location = Cache::remember('geocode-gudang.'.sha1($wilayah), now()->addDays(30), function () use ($wilayah) {
                return Http::acceptJson()
                    ->withUserAgent(config('app.name', 'Smart Agroindustri').'/1.0')
                    ->timeout(10)
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $wilayah.', Indonesia',
                        'format' => 'jsonv2',
                        'limit' => 1,
                        'countrycodes' => 'id',
                    ])
                    ->throw()
                    ->json();
            });
        } catch (ConnectionException|\Illuminate\Http\Client\RequestException $exception) {
            return response()->json(['message' => 'Pencarian lokasi sementara tidak tersedia.'], 502);
        }

        if (empty($location)) {
            return response()->json(['message' => 'Lokasi untuk wilayah tersebut tidak ditemukan.'], 404);
        }

        return response()->json([
            'latitude' => (float) $location[0]['lat'],
            'longitude' => (float) $location[0]['lon'],
            'nama_lokasi' => $location[0]['display_name'],
        ]);
    }

    public function create()
    {
        return view('petani.gudang.create');
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

        $data['jenis_gudang'] = 'petani';
        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
        Gudang::create($data);

        return redirect()->route('petani-gudang.index')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return redirect()->route('petani-gudang.index');
    }

    public function edit(string $id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('petani.gudang.edit', compact('gudang'));
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

        return redirect()->route('petani-gudang.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $gudang = Gudang::findOrFail($id);

        DB::transaction(function () use ($gudang) {
            // Hapus data turunan terlebih dahulu agar foreign key gudang_id
            // tidak menghalangi penghapusan gudang lama.
            $gudang->stoks()->delete();
            $gudang->panens()->delete();
            $gudang->delete();
        });

        return redirect()->route('petani-gudang.index')->with('success', 'Gudang beserta data stok dan panen terkait berhasil dihapus.');
    }
}
