<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stoks = Stok::query()
            ->whereHas('gudang', function($q) use ($user) {
                $q->where('jenis_gudang', 'petani');
                if ($user->role === 'petani') {
                    $q->where('user_id', $user->id);
                }
            })
            ->selectRaw('gudang_id, jenis_kentang_id, grade, SUM(jumlah_stok) as jumlah_stok, SUM(stok_dijual) as stok_dijual, MAX(id) as id')
            ->groupBy('gudang_id', 'jenis_kentang_id', 'grade')
            ->with(['gudang', 'jenisKentang'])
            ->paginate(5, ['*'], 'stok_page');

        $totalMaxQuery = Gudang::where('jenis_gudang', 'petani');
        if ($user->role === 'petani') {
            $totalMaxQuery->where('user_id', $user->id);
        }
        $totalMax = $totalMaxQuery->sum('kapasitas_max');
        $totalStok = $stoks->sum('jumlah_stok');
        $utilitasGudang = $totalMax > 0 ? round(($totalStok / $totalMax) * 100) : 0;

        // Riwayat Aktivitas Pergerakan Stok (Penambahan Panen & Pengurangan Pembelian Koperasi)
        $panenQuery = \App\Models\Panen::with(['jenisKentang', 'gudang']);
        if ($user->role === 'petani') {
            $panenQuery->whereHas('gudang', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $panenLogs = $panenQuery->latest()->get()->map(function ($p) {
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

        $pembelianQuery = \App\Models\Pembelian::with(['jenisKentang', 'koperasi']);
        if ($user->role === 'petani') {
            $pembelianQuery->where('petani_id', $user->id);
        }
        $pembelianLogs = $pembelianQuery->latest()->get()->map(function ($b) {
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
        $user = Auth::user();
        $gudangQuery = Gudang::where('jenis_gudang', 'petani');
        if ($user->role === 'petani') {
            $gudangQuery->where('user_id', $user->id);
        }
        $gudangs = $gudangQuery->get();
        $jenisKentangs = JenisKentang::whereHas('kategoriKentang', function($q) { $q->where('tipe_komoditas', 'konsumsi'); })->get();
        
        $existingStokQuery = Stok::query()
            ->whereHas('gudang', function($q) use ($user) {
                $q->where('jenis_gudang', 'petani');
                if ($user->role === 'petani') {
                    $q->where('user_id', $user->id);
                }
            });
            
        $existingStoks = $existingStokQuery
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

        $user = Auth::user();
        if ($user->role === 'petani') {
            $gudang = Gudang::where('id', $data['gudang_id'])->where('user_id', $user->id)->first();
            if (!$gudang) {
                return back()->withInput()->withErrors(['gudang_id' => 'Gudang tidak ditemukan atau bukan milik Anda.']);
            }
        }

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
        $stok = Stok::with('gudang')->findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'petani' && $stok->gudang->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $gudangQuery = Gudang::where('jenis_gudang', 'petani');
        if ($user->role === 'petani') {
            $gudangQuery->where('user_id', $user->id);
        }
        $gudangs = $gudangQuery->get();
        $jenisKentangs = JenisKentang::whereHas('kategoriKentang', function($q) { $q->where('tipe_komoditas', 'konsumsi'); })->get();
        
        $totalFisikGudang = $stok->jumlah_stok;

        return view('petani.stok.edit', compact('stok', 'gudangs', 'jenisKentangs', 'totalFisikGudang'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'grade' => 'required|string|in:A,B,C',
            'stok_dijual' => 'required|numeric|min:0',
        ]);

        $stok = Stok::with('gudang')->findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'petani' && $stok->gudang->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'petani') {
            $gudang = Gudang::where('id', $data['gudang_id'])->where('user_id', $user->id)->first();
            if (!$gudang) {
                return back()->withInput()->withErrors(['gudang_id' => 'Gudang tidak ditemukan atau bukan milik Anda.']);
            }
        }

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
        $stok = Stok::with('gudang')->findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'petani' && $stok->gudang->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::transaction(function () use ($stok) {
                $stok->delete();
            });
            return redirect()->back()->with('success', 'Stok berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus stok: ' . $e->getMessage());
        }
    }
}
