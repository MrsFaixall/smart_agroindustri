<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Pembayaran::with(['pembelian.petani', 'pembelian.koperasi', 'metodePembayaran']);

        // Filter by role
        if ($user->role === 'petani') {
            $query->whereHas('pembelian', fn($q) => $q->where('petani_id', $user->id));
        } elseif ($user->role === 'koperasi') {
            $query->whereHas('pembelian', fn($q) => $q->where('koperasi_id', $user->id));
        }

        // Total stats (after role filter)
        $totalTransaksi = (clone $query)->count();
        $totalLunas = (clone $query)->whereIn('status', ['lunas', 'berhasil', 'sukses'])->count();
        $totalPending = (clone $query)->whereIn('status', ['belum lunas', 'pending'])->count();
        $totalNilai = (clone $query)->sum('jumlah_bayar');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pembelian.koperasi', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('pembelian.petani', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('metodePembayaran', function($qm) use ($search) {
                    $qm->where('bank', 'like', "%{$search}%")->orWhere('kategori', 'like', "%{$search}%");
                });
            });
        }

        // Period / date range filter
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

        return view('koperasi.daftar-transaksi.index', compact('payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
    }
}
