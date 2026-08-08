<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;

use App\Models\PenjualanBuah;

class PublicTrackingController extends Controller
{
    public function track($token)
    {
        // Ambil data penjualan buah beserta relasi
        $transaksi = PenjualanBuah::with(['koperasi', 'jenisKentang', 'pembeli'])
            ->where('tracking_token', $token)
            ->firstOrFail();

        return view('welcome.lacak', compact('transaksi'));
    }
}
