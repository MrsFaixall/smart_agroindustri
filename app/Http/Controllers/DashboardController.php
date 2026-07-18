<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Panen;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Seluruh master jenis ikut ditampilkan, termasuk yang stoknya masih 0.
        $stokPerJenis = JenisKentang::query()
            ->withSum('stoks', 'jumlah_stok')
            ->orderBy('nama_jenis')
            ->get()
            ->map(function (JenisKentang $jenis) {
                return [
                    'nama' => $jenis->nama_jenis,
                    'total' => (float) ($jenis->stoks_sum_jumlah_stok ?? 0),
                ];
            });

        // Data for Grafik Stok (Panen trends by month for current year)
        $currentYear = date('Y');
        $panenPerBulan = Panen::selectRaw('MONTH(tanggal_panen) as bulan, sum(jumlah_kg) as total')
            ->whereYear('tanggal_panen', $currentYear)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $grafikStokData = [];
        $grafikStokLabels = [];
        $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        for ($i = 1; $i <= 12; $i++) {
            $grafikStokLabels[] = $bulanNames[$i - 1];
            $grafikStokData[] = $panenPerBulan->get($i, 0); // default 0 if no panen in that month
        }

        return view('dashboard.index', [
            'user' => Auth::user(),
            'totalGudang' => Gudang::count(),
            'totalPetani' => User::where('role', 'petani')->count(),
            'totalPengepul' => User::where('role', 'pengepul')->count(),
            'totalJenisKentang' => $stokPerJenis->count(),
            'totalStokKg' => Stok::sum('jumlah_stok'),
            'totalPanenKg' => Panen::sum('jumlah_kg'),
            'stokPerJenis' => $stokPerJenis,
            'grafikStokLabels' => json_encode($grafikStokLabels),
            'grafikStokData' => json_encode($grafikStokData),
        ]);
    }
}
