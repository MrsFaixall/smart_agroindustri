<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\PenjualanBuah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraPembelianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PenjualanBuah::with(['koperasi', 'jenisKentang'])
            ->where('pembeli_id', $user->id);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('jenisKentang', function($qk) use ($search) {
                    $qk->where('nama_jenis', 'like', "%{$search}%");
                })->orWhereHas('koperasi', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->input('end_date'));
        }

        $pembelians = $query->latest()->paginate(10)->withQueryString();
        $totalNilai = $query->sum('total_harga');

        return view('mitra.pembelian.index', compact('pembelians', 'totalNilai'));
    }

    public function bayar($id)
    {
        $transaksi = PenjualanBuah::findOrFail($id);
        $transaksi->update(['status' => 'lunas']);

        // Transfer stok ke Gudang Mitra
        PenjualanBuah::transferStockToMitra($transaksi);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan stok kentang telah masuk ke gudang Anda.');
    }
}
