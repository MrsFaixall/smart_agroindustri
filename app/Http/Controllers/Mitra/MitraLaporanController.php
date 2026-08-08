<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\PenjualanBuah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraLaporanController extends Controller
{
    private function getMonthYear(Request $request) {
        return [
            $request->input('month', date('m')),
            $request->input('year', date('Y'))
        ];
    }

    // --- Pembelian Mitra (Membeli dari Koperasi) ---
    public function pembelian(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenjualanBuah::with(['koperasi', 'jenisKentang'])
            ->where('pembeli_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->paginate(10);
        return view('mitra.laporan.pembelian', compact('month', 'year', 'data'));
    }

    public function exportPembelian(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenjualanBuah::with(['koperasi', 'jenisKentang'])
            ->where('pembeli_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->get();
        return $this->generateCsv('laporan_pembelian_mitra', $month, $year, ['Tanggal', 'Koperasi Penjual', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status'], function($item) {
            return [$item->tanggal_transaksi, $item->koperasi->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Penjualan Mitra (Menjual ke Konsumen) ---
    public function penjualan(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenjualanBuah::with(['pembeli', 'jenisKentang'])
            ->where('koperasi_id', $user->id) // Mitra bertindak sebagai penjual
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->paginate(10);
        return view('mitra.laporan.penjualan', compact('month', 'year', 'data'));
    }

    public function exportPenjualan(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenjualanBuah::with(['pembeli', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->get();
        return $this->generateCsv('laporan_penjualan_mitra', $month, $year, ['Tanggal', 'Pembeli/Konsumen', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status'], function($item) {
            return [$item->tanggal_transaksi, $item->pembeli->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Helper CSV ---
    private function generateCsv($prefix, $month, $year, $headers, $rowCallback, $data) {
        $filename = "{$prefix}_{$year}_{$month}.csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ["Laporan $prefix", "Periode: $month - $year"]);
        fputcsv($handle, []);
        fputcsv($handle, $headers);
        foreach ($data as $item) {
            fputcsv($handle, $rowCallback($item));
        }
        fclose($handle);
        exit;
    }
}
