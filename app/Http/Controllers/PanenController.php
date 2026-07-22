<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Panen;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanenController extends Controller
{
    public function index()
    {
        $panens = Panen::with(['gudang', 'jenisKentang.harga', 'stok'])->latest()->get();
        
        $totalMusimIni = $panens->sum('jumlah_kg');
        $hargaPasar = \App\Models\Harga::avg('harga') ?? 0;
        $menungguBayar = \App\Models\Pembelian::where('status', 'belum lunas')->sum('total_harga');
        
        $gudangs = Gudang::with('stoks')->get();
        $primaryGudang = $gudangs->first();
        
        $activeBatches = \App\Models\Stok::with(['jenisKentang', 'gudang', 'panen'])
            ->where('jumlah_stok', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        return view('petani.panen.index', compact(
            'panens', 
            'totalMusimIni', 
            'hargaPasar', 
            'menungguBayar', 
            'gudangs',
            'primaryGudang',
            'activeBatches'
        ));
    }

    public function create()
    {
        $gudangs = Gudang::with('stoks')->get();
        $jenisKentangs = JenisKentang::all();
        return view('petani.panen.create', compact('gudangs', 'jenisKentangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'jumlah_kg' => 'required|numeric|gt:0',
            'tanggal_panen' => 'required|date',
            'grade' => 'required|string|in:A,B,C',
        ]);

        $gudang = Gudang::with('stoks')->findOrFail($data['gudang_id']);
        $terpakai = $gudang->kapasitas_terpakai;
        $sisaKapasitas = $gudang->kapasitas_max - $terpakai;

        if ($data['jumlah_kg'] > $sisaKapasitas) {
            $sisaText = $sisaKapasitas > 0 ? number_format($sisaKapasitas, 0, ',', '.') . ' Kg' : '0 Kg (Gudang Penuh)';
            return back()->withInput()->withErrors([
                'jumlah_kg' => "Kapasitas gudang '{$gudang->nama_gudang}' tidak mencukupi! Sisa kapasitas yang tersedia hanya {$sisaText}."
            ]);
        }

        DB::transaction(function () use ($data) {
            $panen = Panen::create($data);
            $stok = Stok::firstOrCreate(
                ['gudang_id' => $panen->gudang_id, 'jenis_kentang_id' => $panen->jenis_kentang_id, 'grade' => $panen->grade],
                ['jumlah_stok' => 0, 'panen_id' => null]
            );
            $stok->jumlah_stok += $panen->jumlah_kg;
            $stok->save();
        });

        return redirect()->route('panen.index')->with('success', 'Data panen berhasil disimpan.');
    }

    public function show(string $id)
    {
        return redirect()->route('panen.index');
    }

    public function edit(string $id)
    {
        $panen = Panen::findOrFail($id);
        $gudangs = Gudang::with('stoks')->get();
        $jenisKentangs = JenisKentang::all();

        return view('petani.panen.edit', compact('panen', 'gudangs', 'jenisKentangs'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'jumlah_kg' => 'required|numeric|gt:0',
            'tanggal_panen' => 'required|date',
            'grade' => 'required|string|in:A,B,C',
        ]);

        $panen = Panen::findOrFail($id);

        $gudang = Gudang::with('stoks')->findOrFail($data['gudang_id']);
        $terpakai = $gudang->kapasitas_terpakai;
        if ($panen->gudang_id == $data['gudang_id']) {
            $terpakai -= $panen->jumlah_kg;
        }
        $sisaKapasitas = $gudang->kapasitas_max - $terpakai;

        if ($data['jumlah_kg'] > $sisaKapasitas) {
            $sisaText = $sisaKapasitas > 0 ? number_format($sisaKapasitas, 0, ',', '.') . ' Kg' : '0 Kg (Gudang Penuh)';
            return back()->withInput()->withErrors([
                'jumlah_kg' => "Kapasitas gudang '{$gudang->nama_gudang}' tidak mencukupi! Sisa kapasitas yang tersedia hanya {$sisaText}."
            ]);
        }

        DB::transaction(function () use ($data, $panen) {
            // Revert old stock
            $oldStok = Stok::where('gudang_id', $panen->gudang_id)->where('jenis_kentang_id', $panen->jenis_kentang_id)->where('grade', $panen->grade)->first();
            if ($oldStok) {
                $oldStok->jumlah_stok -= $panen->jumlah_kg;
                $oldStok->save();
            }

            $panen->update($data);
            
            // Add new stock
            $newStok = Stok::firstOrCreate(
                ['gudang_id' => $panen->gudang_id, 'jenis_kentang_id' => $panen->jenis_kentang_id, 'grade' => $panen->grade],
                ['jumlah_stok' => 0, 'panen_id' => null]
            );
            $newStok->jumlah_stok += $panen->jumlah_kg;
            $newStok->save();
        });

        return redirect()->route('panen.index')->with('success', 'Data panen berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $panen = Panen::findOrFail($id);
            
            // Revert stock
            $stok = Stok::where('gudang_id', $panen->gudang_id)->where('jenis_kentang_id', $panen->jenis_kentang_id)->where('grade', $panen->grade)->first();
            if ($stok) {
                $stok->jumlah_stok -= $panen->jumlah_kg;
                $stok->save();
            }
            
            // If stok panen_id matched this panen (old behavior), clear it just in case
            if ($stok && $stok->panen_id == $panen->id) {
                $stok->panen_id = null;
                $stok->save();
            }

            $panen->delete();
        });
        return redirect()->route('panen.index')->with('success', 'Data panen berhasil dihapus.');
    }

    private function syncStokPanen(Panen $panen): void
    {
        Stok::updateOrCreate(
            ['panen_id' => $panen->id],
            [
                'gudang_id' => $panen->gudang_id,
                'jenis_kentang_id' => $panen->jenis_kentang_id,
                'jumlah_stok' => $panen->jumlah_kg,
                'grade' => $panen->grade,
            ]
        );
    }
}
