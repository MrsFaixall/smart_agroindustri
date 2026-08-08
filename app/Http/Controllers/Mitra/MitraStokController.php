<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\Stok;
use App\Models\PenjualanBuah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraStokController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $gudangMitra = Gudang::where('user_id', $user->id)->first();
        if (!$gudangMitra) {
            return redirect()->route('mitra-gudang.index')
                ->with('error', 'Silakan buat gudang mitra terlebih dahulu.');
        }

        $stoks = Stok::query()
            ->where('gudang_id', $gudangMitra->id)
            ->with(['gudang', 'jenisKentang'])
            ->paginate(10, ['*'], 'stok_page');

        $totalMax = $gudangMitra->kapasitas_max ?? 0;
        $totalStok = $stoks->sum('jumlah_stok');
        $utilitasGudang = $totalMax > 0 ? round(($totalStok / $totalMax) * 100) : 0;

        // Inflows: Pembelian dari Koperasi
        $masukLogs = PenjualanBuah::with(['jenisKentang', 'koperasi'])
            ->where('pembeli_id', $user->id)
            ->where('status', 'lunas')
            ->latest()
            ->get()
            ->map(function ($p) {
                return (object)[
                    'type' => 'masuk',
                    'title' => 'Stok Masuk (Pembelian dari Koperasi)',
                    'description' => 'Pembelian ' . ($p->jenisKentang->nama_jenis ?? 'Kentang') . ' dari ' . ($p->koperasi->name ?? 'Koperasi'),
                    'jumlah_kg' => $p->jumlah_kg,
                    'tanggal' => $p->tanggal_transaksi ?? $p->created_at,
                    'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'badge_label' => 'Penambahan (+)',
                    'icon' => '📥',
                    'sign' => '+'
                ];
            });

        // Outflows: Penjualan ke Konsumen
        $keluarLogs = PenjualanBuah::with(['jenisKentang', 'pembeli'])
            ->where('koperasi_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($b) {
                return (object)[
                    'type' => 'keluar',
                    'title' => 'Stok Terjual (Konsumen/Retail)',
                    'description' => 'Penjualan komoditas ' . ($b->jenisKentang->nama_jenis ?? 'Kentang') . ' ke ' . ($b->pembeli->name ?? 'Konsumen'),
                    'jumlah_kg' => $b->jumlah_kg,
                    'tanggal' => $b->tanggal_transaksi ?? $b->created_at,
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

        return view('mitra.stok.index', compact('stoks', 'utilitasGudang', 'aktivitasStoks', 'gudangMitra'));
    }
}
