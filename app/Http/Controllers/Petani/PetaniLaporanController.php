<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;

use App\Models\PengajuanBenih;
use App\Models\DistribusiBenih;
use App\Models\PenawaranPanen;
use App\Models\PenjualanBuah;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniLaporanController extends Controller
{
    private function getMonthYear(Request $request) {
        return [
            $request->input('month', date('m')),
            $request->input('year', date('Y'))
        ];
    }

    // --- Pengajuan Benih ---
    public function pengajuanBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PengajuanBenih::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->paginate(10);
        return view('petani.laporan.pengajuan_benih.index', compact('month', 'year', 'data'));
    }
    public function exportPengajuanBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PengajuanBenih::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        return $this->generateCsv('laporan_pengajuan_benih', $month, $year, ['Tanggal', 'Koperasi', 'Komoditas', 'Jumlah (Kg)', 'Status'], function($item) {
            return [$item->created_at->format('Y-m-d'), $item->koperasi->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->status];
        }, $data);
    }

    // --- Distribusi Benih ---
    public function distribusiBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = DistribusiBenih::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->paginate(10);
        return view('petani.laporan.distribusi_benih.index', compact('month', 'year', 'data'));
    }
    public function exportDistribusiBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = DistribusiBenih::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->get();
        return $this->generateCsv('laporan_distribusi_benih', $month, $year, ['Tanggal', 'Koperasi', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status Pembayaran'], function($item) {
            return [$item->tanggal_transaksi, $item->koperasi->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Penawaran Panen ---
    public function penawaranPanen(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenawaranPanen::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->paginate(10);
        return view('petani.laporan.penawaran_panen.index', compact('month', 'year', 'data'));
    }
    public function exportPenawaranPanen(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenawaranPanen::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        return $this->generateCsv('laporan_penawaran_panen', $month, $year, ['Tanggal', 'Koperasi', 'Komoditas', 'Kuantitas (Kg)', 'Harga/Kg', 'Status'], function($item) {
            return [$item->created_at->format('Y-m-d'), $item->koperasi->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->harga_tawaran_petani, $item->status];
        }, $data);
    }

    // --- Pembelian Buah/Benih (Riwayat Pembelian Petani) ---
    public function pembelian(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenjualanBuah::with(['koperasi', 'jenisKentang'])
            ->where('pembeli_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->paginate(10);
        return view('petani.laporan.pembelian.index', compact('month', 'year', 'data'));
    }
    public function exportPembelian(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenjualanBuah::with(['koperasi', 'jenisKentang'])
            ->where('pembeli_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->get();
        return $this->generateCsv('laporan_pembelian_petani', $month, $year, ['Tanggal', 'Koperasi', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status'], function($item) {
            return [$item->tanggal_transaksi, $item->koperasi->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Penjualan Panen (Riwayat Penjualan Petani) ---
    public function penjualan(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembelian::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('tanggal_pembelian', $month)->whereYear('tanggal_pembelian', $year)->paginate(10);
        return view('petani.laporan.penjualan.index', compact('month', 'year', 'data'));
    }
    public function exportPenjualan(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembelian::with(['koperasi', 'jenisKentang'])
            ->where('petani_id', $user->id)
            ->whereMonth('tanggal_pembelian', $month)->whereYear('tanggal_pembelian', $year)->get();
        return $this->generateCsv('laporan_penjualan_panen', $month, $year, ['Tanggal', 'Koperasi Pembeli', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status'], function($item) {
            return [$item->tanggal_pembelian, $item->koperasi->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Pembayaran ---
    public function pembayaran(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembayaran::with(['pembelian.koperasi', 'metodePembayaran'])
            ->whereHas('pembelian', function($q) use ($user) {
                $q->where('petani_id', $user->id);
            })
            ->whereMonth('tanggal_pembayaran', $month)->whereYear('tanggal_pembayaran', $year)->paginate(10);
        return view('petani.laporan.pembayaran.index', compact('month', 'year', 'data'));
    }
    public function exportPembayaran(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembayaran::with(['pembelian.koperasi', 'metodePembayaran'])
            ->whereHas('pembelian', function($q) use ($user) {
                $q->where('petani_id', $user->id);
            })
            ->whereMonth('tanggal_pembayaran', $month)->whereYear('tanggal_pembayaran', $year)->get();
        return $this->generateCsv('laporan_pemasukan_pembayaran', $month, $year, ['Tanggal', 'Referensi', 'Pengirim (Koperasi)', 'Jumlah Terima', 'Metode', 'Status'], function($item) {
            return [$item->tanggal_pembayaran, $item->kode_pembayaran, $item->pembelian->koperasi->name ?? '-', $item->jumlah_bayar, $item->metodePembayaran->nama_metode ?? '-', $item->status];
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
