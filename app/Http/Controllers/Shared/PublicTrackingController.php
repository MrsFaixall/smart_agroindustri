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

        $mapSvg = $this->generateRouteSvg(
            $transaksi->koperasi->name ?? 'Koperasi',
            $transaksi->pembeli->name ?? 'Mitra'
        );

        return view('welcome.lacak', compact('transaksi', 'mapSvg'));
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

        $mapSvg = $this->generateRouteSvg(
            $transaksi->koperasi->name ?? 'Koperasi',
            $transaksi->pembeli->name ?? 'Mitra'
        );

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
                'mapSvg' => $mapSvg,
            ]
        ]);
    }

    private function generateRouteSvg($originName, $destName)
    {
        // Extract origin label
        $originLabel = 'KOPERASI';
        if (stripos($originName, 'dieng') !== false) {
            $originLabel = 'DIENG';
        } elseif (stripos($originName, 'pangalengan') !== false) {
            $originLabel = 'PANGALENGAN';
        } elseif (stripos($originName, 'bromo') !== false) {
            $originLabel = 'BROMO';
        } elseif (stripos($originName, 'garut') !== false || stripos($originName, 'faisal') !== false) {
            $originLabel = 'GARUT';
        } else {
            $words = explode(' ', trim($originName));
            $originLabel = strtoupper($words[0] ?? 'KOPERASI');
        }

        // Extract destination label
        $destLabel = 'MITRA';
        if (stripos($destName, 'champ') !== false || stripos($destName, 'horti') !== false) {
            $destLabel = 'CHAMP';
        } elseif (stripos($destName, 'jakarta') !== false || stripos($destName, 'kramat') !== false || stripos($destName, 'gading') !== false) {
            $destLabel = 'JAKARTA';
        } elseif (stripos($destName, 'cikarang') !== false || stripos($destName, 'bekasi') !== false) {
            $destLabel = 'CIKARANG';
        } elseif (stripos($destName, 'bandung') !== false) {
            $destLabel = 'BANDUNG';
        } else {
            $words = explode(' ', trim($destName));
            $destLabel = strtoupper($words[0] ?? 'MITRA');
        }

        $pathD = 'M60 80 Q 120 100 160 60 T 240 40';

        return '<svg class="w-full h-full" viewBox="0 0 300 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 20 H290 M10 40 H290 M10 60 H290 M10 80 H290 M10 100 H290" stroke="#f1f5f9" stroke-width="1"/>
            <path d="M50 10 V110 M100 10 V110 M150 10 V110 M200 10 V110 M250 10 V110" stroke="#f1f5f9" stroke-width="1"/>
            
            <!-- Route path (dashed background) -->
            <path d="' . $pathD . '" stroke="#e2e8f0" stroke-width="4" stroke-linecap="round"/>
            
            <path d="' . $pathD . '" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-dasharray="6 4" class="animate-route"/>
            <path d="' . $pathD . '" stroke="#10b981" stroke-width="1.5" stroke-linecap="round"/>
            
            <circle cx="60" cy="80" r="6" fill="#f59e0b" stroke="white" stroke-width="2" class="animate-pulse"/>
            <circle cx="60" cy="80" r="4" fill="#f59e0b"/>
            <text x="52" y="95" fill="#64748b" class="text-[8px] font-extrabold outfit">' . $originLabel . '</text>
            
            <circle cx="240" cy="40" r="4" fill="#ef4444" stroke="white" stroke-width="1.5"/>
            <circle cx="240" cy="40" r="2.5" fill="#ef4444"/>
            <text x="225" y="32" fill="#64748b" class="text-[8px] font-extrabold outfit">' . $destLabel . '</text>
            
            <!-- Monospace route text label (bottom left) -->
            <text x="15" y="105" fill="#94a3b8" class="text-[7px] font-mono tracking-wider font-bold">' . $originLabel . ' → ' . $destLabel . '</text>
            
            <!-- Animated Truck Icon -->
            <g>
                <path d="M-6 -2 H1 L3 0 V2 H-6 V-2 Z" fill="#0f172a"/>
                <rect x="-8" y="-1" width="2.5" height="2.5" fill="#38bdf8"/>
                <circle cx="-3.5" cy="2.5" r="1" fill="#64748b"/>
                <circle cx="1.5" cy="2.5" r="1" fill="#64748b"/>
                <animateMotion dur="7s" repeatCount="indefinite" rotate="auto" path="' . $pathD . '"/>
            </g>
        </svg>';
    }
}
