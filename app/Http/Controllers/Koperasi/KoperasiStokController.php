<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\Pembelian;
use App\Models\PenjualanBuah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KoperasiStokController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stoks = Stok::query()
            ->whereHas('gudang', function($q) use ($user) {
                $q->where('jenis_gudang', 'koperasi');
            })
            ->selectRaw('gudang_id, jenis_kentang_id, grade, SUM(jumlah_stok) as jumlah_stok, SUM(alokasi_pt_camp) as alokasi_pt_camp, SUM(alokasi_konsumen) as alokasi_konsumen, MAX(id) as id')
            ->groupBy('gudang_id', 'jenis_kentang_id', 'grade')
            ->with(['gudang', 'jenisKentang'])
            ->paginate(5, ['*'], 'stok_page');

        $totalMaxQuery = Gudang::where('jenis_gudang', 'koperasi');
        $totalMax = $totalMaxQuery->sum('kapasitas_max');
        $totalStok = $stoks->sum('jumlah_stok');
        $utilitasGudang = $totalMax > 0 ? round(($totalStok / $totalMax) * 100) : 0;

        // Riwayat Aktivitas Pergerakan Stok (Stok Masuk dari Petani & Stok Keluar ke Pembeli/Mitra)
        $pembelianQuery = Pembelian::with(['jenisKentang', 'petani'])->where('status', '!=', 'ditolak');
        if ($user->role === 'koperasi') {
            $pembelianQuery->where('koperasi_id', $user->id);
        }
        $masukLogs = $pembelianQuery->latest()->get()->map(function ($p) {
            return (object)[
                'type' => 'masuk',
                'title' => 'Stok Masuk (Pembelian dari Petani)',
                'description' => 'Pembelian ' . ($p->jenisKentang->nama_jenis ?? 'Kentang') . ' dari ' . ($p->petani->name ?? 'Petani'),
                'jumlah_kg' => $p->jumlah_kg,
                'tanggal' => $p->tanggal_pembelian ?? $p->created_at,
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'badge_label' => 'Penambahan (+)',
                'icon' => '📥',
                'sign' => '+'
            ];
        });

        $penjualanQuery = PenjualanBuah::with(['jenisKentang', 'pembeli']);
        if ($user->role === 'koperasi') {
            $penjualanQuery->where('koperasi_id', $user->id);
        }
        $keluarLogs = $penjualanQuery->latest()->get()->map(function ($b) {
            return (object)[
                'type' => 'keluar',
                'title' => 'Stok Terjual (Mitra/Pasar)',
                'description' => 'Penjualan komoditas ' . ($b->jenisKentang->nama_jenis ?? 'Kentang') . ' ke ' . ($b->pembeli->name ?? 'Pembeli'),
                'jumlah_kg' => $b->jumlah_kg,
                'tanggal' => $b->tanggal_penjualan ?? $b->created_at,
                'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                'badge_label' => 'Pengurangan (-)',
                'icon' => '📤',
                'sign' => '-'
            ];
        });

        $allLogs = $masukLogs->concat($keluarLogs)->sortByDesc('tanggal')->values();
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('aktivitas_page');
        $perPage = 5;
        $aktivitasStoks = new \Illuminate\Pagination\LengthAwarePaginator(
            $allLogs->slice(($page - 1) * $perPage, $perPage)->values(),
            $allLogs->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'aktivitas_page']
        );

        return view('koperasi.stok_koperasi.index', compact('stoks', 'utilitasGudang', 'aktivitasStoks'));
    }

    // Modal action: Update status stok_dijual
    public function create()
    {
        $user = Auth::user();
        $gudangQuery = Gudang::where('jenis_gudang', 'koperasi');
        $gudangs = $gudangQuery->get();
        $jenisKentangs = JenisKentang::whereHas('kategoriKentang', function($q) { $q->where('tipe_komoditas', 'konsumsi'); })->get();
        
        $existingStokQuery = Stok::query()
            ->whereHas('gudang', function($q) {
                $q->where('jenis_gudang', 'koperasi');
            });
            
        $existingStoks = $existingStokQuery
            ->selectRaw('gudang_id, jenis_kentang_id, grade, SUM(jumlah_stok) as total_gudang, SUM(alokasi_pt_camp) as total_camp, SUM(alokasi_konsumen) as total_konsumen')
            ->groupBy('gudang_id', 'jenis_kentang_id', 'grade')
            ->get();
        
        return view('koperasi.stok_koperasi.create', compact('gudangs', 'jenisKentangs', 'existingStoks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'grade' => 'required|string|max:10',
            'jumlah_stok' => 'required|numeric|min:0.01',
        ]);

        $gudang = Gudang::findOrFail($data['gudang_id']);

        DB::transaction(function () use ($data, $gudang) {
            $stok = Stok::where('gudang_id', $data['gudang_id'])
                ->where('jenis_kentang_id', $data['jenis_kentang_id'])
                ->where('grade', $data['grade'])
                ->first();

            if ($stok) {
                $stok->increment('jumlah_stok', $data['jumlah_stok']);
            } else {
                Stok::create($data);
            }
        });

        return redirect()->route('koperasi.stok-koperasi.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'alokasi_pt_camp' => 'required|numeric|min:0',
            'alokasi_konsumen' => 'required|numeric|min:0'
        ]);

        $stokReference = Stok::findOrFail($id);
        
        $stoks = Stok::where('gudang_id', $stokReference->gudang_id)
            ->where('jenis_kentang_id', $stokReference->jenis_kentang_id)
            ->where('grade', $stokReference->grade)
            ->get();
            
        $totalFisik = $stoks->sum('jumlah_stok');
        
        $totalAlokasi = $request->alokasi_pt_camp + $request->alokasi_konsumen;
        
        if ($totalAlokasi > $totalFisik) {
            return back()->with('error', 'Total alokasi (PT CAMP + Konsumen) tidak boleh melebihi total stok fisik yang ada di gudang saat ini.');
        }

        // Distribusi alokasi ke baris-baris stok (kalau ada multiple row)
        $sisaCamp = $request->alokasi_pt_camp;
        $sisaKonsumen = $request->alokasi_konsumen;
        
        foreach ($stoks as $s) {
            $alokasiCampBaru = min($sisaCamp, $s->jumlah_stok);
            $s->alokasi_pt_camp = $alokasiCampBaru;
            $sisaCamp -= $alokasiCampBaru;
            
            $alokasiKonsumenBaru = min($sisaKonsumen, $s->jumlah_stok - $s->alokasi_pt_camp);
            $s->alokasi_konsumen = $alokasiKonsumenBaru;
            $sisaKonsumen -= $alokasiKonsumenBaru;
            
            $s->save();
        }

        return redirect()->route('koperasi.stok-koperasi.index')->with('success', 'Alokasi stok Koperasi berhasil diperbarui.');
    }
}
