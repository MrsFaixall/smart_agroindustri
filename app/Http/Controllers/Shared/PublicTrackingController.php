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

    public function apiTrack($token)
    {
        $transaksi = PenjualanBuah::with(['koperasi', 'jenisKentang', 'pembeli'])
            ->where('tracking_token', $token)
            ->first();

        if (!$transaksi) {
            return response()->json(['success' => false, 'message' => 'Token pelacakan tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaksi->tracking_token,
                'varietas' => $transaksi->jenisKentang->nama_jenis ?? '-',
                'grade' => 'Grade ' . ($transaksi->grade ?? 'A'),
                'berat' => number_format($transaksi->kuantitas, 0, ',', '.') . ' Kg',
                'petani' => 'Mitra Kelompok Tani Koperasi',
                'koperasi' => $transaksi->koperasi->name ?? '-',
                'lokasi' => $transaksi->koperasi->alamat ?? 'Sentra Tani Koperasi',
                'ketinggian' => '1.850 mdpl',
                'tanggalPanen' => $transaksi->created_at->translatedFormat('d F Y'),
                'tanggalKirim' => \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y'),
                'tujuan' => $transaksi->pembeli->name ?? 'Tujuan Mitra',
                'jarak' => '240 Km',
                'ruteInfo' => $transaksi->routing_path ?? 'Rute Logistik Teroptimal',
                'truckNo' => 'B 9042 CHAMP',
                'waktuTempuh' => $transaksi->estimasi_waktu ?? '3 Jam 30 Menit',
                'suhuKargo' => '16°C (Optimal)',
            ]
        ]);
    }
}
