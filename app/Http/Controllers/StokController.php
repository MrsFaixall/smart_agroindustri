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
        $stoks = Stok::query()
            ->selectRaw('gudang_id, jenis_kentang_id, grade, SUM(jumlah_stok) as jumlah_stok, SUM(stok_dijual) as stok_dijual, MAX(id) as id')
            ->groupBy('gudang_id', 'jenis_kentang_id', 'grade')
            ->with(['gudang', 'jenisKentang'])
            ->paginate(5, ['*'], 'stok_page');

        $totalMax = Gudang::sum('kapasitas_max');
        $totalStok = $stoks->sum('jumlah_stok');
        $utilitasGudang = $totalMax > 0 ? round(($totalStok / $totalMax) * 100) : 0;

        // Riwayat Aktivitas Pergerakan Stok (Penambahan Panen & Pengurangan Pembelian Koperasi)
        $panenLogs = \App\Models\Panen::with(['jenisKentang', 'gudang'])->latest()->get()->map(function ($p) {
            return (object)[
                'type' => 'masuk',
                'title' => 'Stok Masuk (Hasil Panen Baru)',
                'description' => 'Panen ' . ($p->jenisKentang->nama_jenis ?? 'Kentang') . ' Grade ' . $p->grade . ' masuk ke ' . ($p->gudang->nama_gudang ?? 'Gudang Utama'),
                'jumlah_kg' => $p->jumlah_kg,
                'tanggal' => $p->tanggal_panen ?? $p->created_at,
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'badge_label' => 'Penambahan (+)',
                'icon' => '📥',
                'sign' => '+'
            ];
        });

        $pembelianLogs = \App\Models\Pembelian::with(['jenisKentang', 'koperasi'])->latest()->get()->map(function ($b) {
            return (object)[
                'type' => 'keluar',
                'title' => 'Stok Dibeli / Keluar (Koperasi)',
                'description' => 'Pembelian komoditas ' . ($b->jenisKentang->nama_jenis ?? 'Kentang') . ' oleh ' . ($b->koperasi->name ?? 'Koperasi') . ' (Status: ' . ucfirst($b->status) . ')',
                'jumlah_kg' => $b->jumlah_kg,
                'tanggal' => $b->tanggal_pembelian ?? $b->created_at,
                'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                'badge_label' => 'Pengurangan (-)',
                'icon' => '📤',
                'sign' => '-'
            ];
        });

        $allLogs = $panenLogs->concat($pembelianLogs)->sortByDesc('tanggal')->values();
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('aktivitas_page');
        $perPage = 5;
        $aktivitasStoks = new \Illuminate\Pagination\LengthAwarePaginator(
            $allLogs->slice(($page - 1) * $perPage, $perPage)->values(),
            $allLogs->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'aktivitas_page']
        );

        return view('petani.stok.index', compact('stoks', 'utilitasGudang', 'aktivitasStoks'));
    }

    public function create()
    {
        $gudangs = Gudang::all();
        $jenisKentangs = JenisKentang::all();
        
        $existingStoks = Stok::query()
            ->selectRaw('gudang_id, jenis_kentang_id, grade, SUM(jumlah_stok) as total_gudang, SUM(stok_dijual) as total_dijual')
            ->groupBy('gudang_id', 'jenis_kentang_id', 'grade')
            ->get();

        return view('petani.stok.create', compact('gudangs', 'jenisKentangs', 'existingStoks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'grade' => 'required|string|in:A,B,C',
            'stok_dijual' => 'required|numeric|min:0',
        ]);

        $stoks = Stok::where('gudang_id', $data['gudang_id'])
            ->where('jenis_kentang_id', $data['jenis_kentang_id'])
            ->where('grade', $data['grade'])
            ->get();

        $totalFisikGudang = $stoks->sum('jumlah_stok');

        if ($stoks->isEmpty() || $totalFisikGudang <= 0) {
            return back()->withInput()->withErrors([
                'stok_dijual' => 'Belum ada stok hasil panen fisik tersimpan di gudang ini untuk komoditas dan grade terpilih.'
            ]);
        }

        if ($data['stok_dijual'] > $totalFisikGudang) {
            $maxText = number_format($totalFisikGudang, 0, ',', '.') . ' Kg';
            return back()->withInput()->withErrors([
                'stok_dijual' => "Jumlah alokasi dijual tidak boleh melebihi total stok fisik yang ada di gudang ({$maxText})."
            ]);
        }

        $stok = $stoks->first();
        $stok->stok_dijual = $data['stok_dijual'];
        $stok->save();

        return redirect()->route('stok.index')->with('success', 'Alokasi stok siap dijual berhasil diperbarui tanpa menambah volume fisik gudang.');
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
            'grade' => 'required|string|in:A,B,C',
            'stok_dijual' => 'required|numeric|min:0',
        ]);

        $stok = Stok::findOrFail($id);

        $totalFisikGudang = $stok->jumlah_stok;

        if ($data['stok_dijual'] > $totalFisikGudang) {
            $maxText = number_format($totalFisikGudang, 0, ',', '.') . ' Kg';
            return back()->withInput()->withErrors([
                'stok_dijual' => "Jumlah alokasi dijual tidak boleh melebihi stok fisik di gudang ({$maxText})."
            ]);
        }

        $stok->update([
            'gudang_id' => $data['gudang_id'],
            'jenis_kentang_id' => $data['jenis_kentang_id'],
            'grade' => $data['grade'],
            'stok_dijual' => $data['stok_dijual'],
        ]);

        return redirect()->route('stok.index')->with('success', 'Alokasi stok berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Stok::findOrFail($id)->delete();
        return redirect()->route('stok.index')->with('success', 'Stok berhasil dihapus.');
    }
}
