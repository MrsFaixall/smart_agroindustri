<?php

namespace App\Http\Controllers;

use App\Models\PengajuanBenih;
use App\Models\DistribusiBenih;
use App\Models\PenawaranPanen;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoperasiLaporanController extends Controller
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
        $data = PengajuanBenih::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->paginate(10);
        return view('koperasi.laporan.pengajuan_benih.index', compact('month', 'year', 'data'));
    }
    public function exportPengajuanBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PengajuanBenih::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        return $this->generateCsv('laporan_pengajuan_benih', $month, $year, ['Tanggal', 'Petani', 'Komoditas', 'Jumlah (Kg)', 'Status'], function($item) {
            return [$item->created_at->format('Y-m-d'), $item->petani->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->status];
        }, $data);
    }

    // --- Distribusi Benih ---
    public function distribusiBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = DistribusiBenih::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->paginate(10);
        return view('koperasi.laporan.distribusi_benih.index', compact('month', 'year', 'data'));
    }
    public function exportDistribusiBenih(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = DistribusiBenih::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year)->get();
        return $this->generateCsv('laporan_distribusi_benih', $month, $year, ['Tanggal', 'Petani', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status Pembayaran'], function($item) {
            return [$item->tanggal_transaksi, $item->petani->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Penawaran Panen ---
    public function penawaranPanen(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenawaranPanen::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->paginate(10);
        return view('koperasi.laporan.penawaran_panen.index', compact('month', 'year', 'data'));
    }
    public function exportPenawaranPanen(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = PenawaranPanen::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        return $this->generateCsv('laporan_penawaran_panen', $month, $year, ['Tanggal', 'Petani', 'Komoditas', 'Kuantitas (Kg)', 'Harga/Kg', 'Status'], function($item) {
            return [$item->created_at->format('Y-m-d'), $item->petani->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->harga_tawaran_petani, $item->status];
        }, $data);
    }

    // --- Pembelian ---
    public function pembelian(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembelian::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('tanggal_pembelian', $month)->whereYear('tanggal_pembelian', $year)->paginate(10);
        return view('koperasi.laporan.pembelian.index', compact('month', 'year', 'data'));
    }
    public function exportPembelian(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembelian::with(['petani', 'jenisKentang'])
            ->where('koperasi_id', $user->id)
            ->whereMonth('tanggal_pembelian', $month)->whereYear('tanggal_pembelian', $year)->get();
        return $this->generateCsv('laporan_pembelian', $month, $year, ['Tanggal', 'Petani', 'Komoditas', 'Jumlah (Kg)', 'Total Harga', 'Status'], function($item) {
            return [$item->tanggal_pembelian, $item->petani->name ?? '-', $item->jenisKentang->nama_jenis ?? '-', $item->jumlah_kg, $item->total_harga, $item->status];
        }, $data);
    }

    // --- Pembayaran ---
    public function pembayaran(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembayaran::with(['pembelian.petani', 'metodePembayaran'])
            ->whereHas('pembelian', function($q) use ($user) {
                $q->where('koperasi_id', $user->id);
            })
            ->whereMonth('tanggal_pembayaran', $month)->whereYear('tanggal_pembayaran', $year)->paginate(10);
        return view('koperasi.laporan.pembayaran.index', compact('month', 'year', 'data'));
    }
    public function exportPembayaran(Request $request) {
        $user = Auth::user();
        [$month, $year] = $this->getMonthYear($request);
        $data = Pembayaran::with(['pembelian.petani', 'metodePembayaran'])
            ->whereHas('pembelian', function($q) use ($user) {
                $q->where('koperasi_id', $user->id);
            })
            ->whereMonth('tanggal_pembayaran', $month)->whereYear('tanggal_pembayaran', $year)->get();
        return $this->generateCsv('laporan_pembayaran_keluar', $month, $year, ['Tanggal', 'Referensi', 'Penerima (Petani)', 'Jumlah Bayar', 'Metode', 'Status'], function($item) {
            return [$item->tanggal_pembayaran, $item->kode_pembayaran, $item->pembelian->petani->name ?? '-', $item->jumlah_bayar, $item->metodePembayaran->nama_metode ?? '-', $item->status];
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
