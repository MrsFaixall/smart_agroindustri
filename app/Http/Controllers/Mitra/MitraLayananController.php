<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\PenjualanBuah;
use Illuminate\Support\Facades\Auth;

class MitraLayananController extends Controller
{
    public function riwayatPembelian()
    {
        $user = Auth::user();
        $transaksis = PenjualanBuah::with(['koperasi', 'jenisKentang', 'pembayaranPenjualans'])
            ->where('pembeli_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return view('mitra.layanan.riwayat-pembelian', compact('transaksis'));
    }

    public function riwayatPenjualan()
    {
        $user = Auth::user();
        $transaksis = PenjualanBuah::with(['pembeli', 'jenisKentang', 'pembayaranPenjualans'])
            ->where('koperasi_id', $user->id) // Mitra bertindak sebagai penjual
            ->latest()
            ->paginate(10);
            
        return view('mitra.layanan.riwayat-penjualan', compact('transaksis'));
    }
}
