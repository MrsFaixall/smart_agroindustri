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

        $gudang = \App\Models\Gudang::where('user_id', $transaksi->pembeli_id)->first();

        return view('welcome.lacak', compact('transaksi', 'gudang'));
    }

    public function apiTrack($token)
    {
        $transaksi = PenjualanBuah::with(['koperasi', 'jenisKentang', 'pembeli'])
            ->where('tracking_token', $token)
            ->first();

        if (!$transaksi) {
            return response()->json(['success' => false, 'message' => 'Token pelacakan tidak ditemukan.'], 404);
        }

        $rawGrade = $transaksi->grade ?? 'A';
        $gradeLetter = trim(str_ireplace('Grade', '', $rawGrade));

        $gudang = \App\Models\Gudang::where('user_id', $transaksi->pembeli_id)->first();
        $lat = $gudang->latitude ?? -7.22647805;
        $lng = $gudang->longitude ?? 107.90107369;
        
        $mapEmbed = '<iframe class="w-full h-full border-0" src="https://maps.google.com/maps?q=' . $lat . ',' . $lng . '&hl=id&z=15&output=embed" allowfullscreen></iframe>';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaksi->tracking_token,
                'varietas' => $transaksi->jenisKentang->nama_jenis ?? '-',
                'grade' => 'Grade ' . ($gradeLetter ?: 'A'),
                'berat' => number_format($transaksi->jumlah_kg ?? 0, 0, ',', '.') . ' Kg',
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
                'mapEmbed' => $mapEmbed,
            ]
        ]);
    }
}
