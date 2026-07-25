<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoperasiGudangStokController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::where('jenis_gudang', 'koperasi')->with(['stoks'])->get();

        $stoks = Stok::query()
            ->whereHas('gudang', function ($q) {
                $q->where('jenis_gudang', 'koperasi');
            })
            ->selectRaw('gudang_id, jenis_kentang_id, grade, SUM(jumlah_stok) as jumlah_stok, SUM(stok_dijual) as stok_dijual, MAX(id) as id')
            ->groupBy('gudang_id', 'jenis_kentang_id', 'grade')
            ->with(['gudang', 'jenisKentang'])
            ->get();

        $totalMax = $gudangs->sum('kapasitas_max');
        $totalStok = $stoks->sum('jumlah_stok');
        $utilitasGudang = $totalMax > 0 ? round(($totalStok / $totalMax) * 100) : 0;

        // Map data for map visualization
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

        return view('koperasi.gudang&stok.index', compact('gudangs', 'stoks', 'utilitasGudang', 'mapGudangs'));
    }

    public function createGudang()
    {
        return view('koperasi.gudang&stok.create_gudang');
    }

    public function storeGudang(Request $request)
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
            'kapasitas_max' => 'required|numeric|min:1',
        ]);

        $data['jenis_gudang'] = 'koperasi';
        $data['status'] = 'aktif';

        Gudang::create($data);

        return redirect()->route('koperasi.gudang-stok.index')->with('success', 'Gudang Koperasi berhasil ditambahkan.');
    }

    public function editGudang($id)
    {
        $gudang = Gudang::where('jenis_gudang', 'koperasi')->findOrFail($id);
        return view('koperasi.gudang&stok.edit_gudang', compact('gudang'));
    }

    public function updateGudang(Request $request, $id)
    {
        $gudang = Gudang::where('jenis_gudang', 'koperasi')->findOrFail($id);

        $data = $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'kapasitas_max' => 'required|numeric|min:1',
        ]);

        $gudang->update($data);

        return redirect()->route('koperasi.gudang-stok.index')->with('success', 'Gudang Koperasi berhasil diperbarui.');
    }

    public function destroyGudang($id)
    {
        $gudang = Gudang::where('jenis_gudang', 'koperasi')->findOrFail($id);
        DB::transaction(function () use ($gudang) {
            $gudang->stoks()->delete();
            $gudang->delete();
        });

        return redirect()->route('koperasi.gudang-stok.index')->with('success', 'Gudang Koperasi beserta data stok terkait berhasil dihapus.');
    }

    // Stok Adjustment
    public function editStok($id)
    {
        $stok = Stok::findOrFail($id);
        $gudangs = Gudang::where('jenis_gudang', 'koperasi')->get();
        $jenisKentangs = JenisKentang::all(); // can edit benih or buah

        return view('koperasi.gudang&stok.edit_stok', compact('stok', 'gudangs', 'jenisKentangs'));
    }

    public function updateStok(Request $request, $id)
    {
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'grade' => 'required|string|in:A,B,C',
            'stok_dijual' => 'required|numeric|min:0',
            'jumlah_stok' => 'required|numeric|min:0',
        ]);

        $stok = Stok::findOrFail($id);
        $stok->update($data);

        return redirect()->route('koperasi.gudang-stok.index')->with('success', 'Penyesuaian stok Koperasi berhasil disimpan.');
    }

    public function destroyStok($id)
    {
        Stok::findOrFail($id)->delete();
        return redirect()->route('koperasi.gudang-stok.index')->with('success', 'Data stok Koperasi berhasil dihapus.');
    }
}
