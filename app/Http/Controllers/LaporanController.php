<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Stok;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month and year
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Base queries with month/year filters
        $pembelianQuery = Pembelian::whereYear('tanggal_pembelian', $year)
                                   ->whereMonth('tanggal_pembelian', $month);
                                   
        $pembayaranQuery = Pembayaran::whereYear('tanggal_pembayaran', $year)
                                     ->whereMonth('tanggal_pembayaran', $month);
        
        // Calculate summary metrics
        $totalPembelian = $pembelianQuery->sum('total_harga');
        
        // Total Stok Akhir is usually total accumulated stock currently available in warehouse
        // So we don't filter it by month/year unless specifically requested.
        $totalStokAkhir = Stok::sum('jumlah_stok'); 
        
        $totalPembayaran = $pembayaranQuery->sum('jumlah_bayar');
        
        // Asumsi Laba Kotor 20% dari total pembelian
        $labaKotor = $totalPembelian * 0.20;

        // Data for the Chart (Aggregated per month in the selected year)
        // Adjusting query depending on DB connection. For MySQL we use MONTH()
        $grafikData = Pembelian::select(
                DB::raw('MONTH(tanggal_pembelian) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_pembelian', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Prepare an array with 0 for each month (Jan-Dec)
        $chartData = array_fill(1, 12, 0);
        foreach ($grafikData as $data) {
            $chartData[(int)$data->bulan] = $data->total;
        }

        return view('laporan.index', compact(
            'month', 
            'year', 
            'totalPembelian', 
            'totalStokAkhir', 
            'totalPembayaran', 
            'labaKotor',
            'chartData'
        ));
    }
}
