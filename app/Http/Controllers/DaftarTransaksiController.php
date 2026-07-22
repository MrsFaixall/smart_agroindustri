<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DaftarTransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Total stats before pagination
        $totalTransaksi = Pembayaran::count();
        $totalLunas = Pembayaran::whereIn('status', ['lunas', 'berhasil', 'sukses'])->count();
        $totalPending = Pembayaran::whereIn('status', ['belum lunas', 'pending'])->count();
        $totalNilai = Pembayaran::sum('jumlah_bayar');

        $query = Pembayaran::with(['pembelian.petani', 'pembelian.pengepul', 'metodePembayaran']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pembelian.pengepul', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('pembelian.petani', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('metodePembayaran', function($qm) use ($search) {
                    $qm->where('bank', 'like', "%{$search}%")->orWhere('kategori', 'like', "%{$search}%");
                });
            });
        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $query->whereDate('tanggal_pembayaran', now()->toDateString());
            } elseif ($request->period === 'this_week') {
                $query->whereBetween('tanggal_pembayaran', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            } elseif ($request->period === 'this_month') {
                $query->whereYear('tanggal_pembayaran', now()->year)->whereMonth('tanggal_pembayaran', now()->month);
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pembayaran', [$request->start_date, $request->end_date]);
        }

        $payments = $query->latest()->paginate(5)->withQueryString();

        if (view()->exists('pengepul.daftar-transaksi.index')) {
            return view('pengepul.daftar-transaksi.index', compact('payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
        }

        return view('pengepul.daftar-tranksaksi.index', compact('payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
    }
}
