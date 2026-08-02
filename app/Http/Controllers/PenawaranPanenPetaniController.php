<?php

namespace App\Http\Controllers;

use App\Models\PenawaranPanen;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenawaranPanenPetaniController extends Controller
{
    public function index()
    {
        $penawarans = PenawaranPanen::with(['jenisKentang', 'koperasi', 'gudang'])
            ->where('petani_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        $hargaPasars = \App\Models\HargaPasar::get()->keyBy('jenis_kentang_id');
            
        return view('petani.penawaran-panen.index', compact('penawarans', 'hargaPasars'));
    }

    public function create(Request $request)
    {
        $stok_id = $request->query('stok_id');
        $selectedStok = null;
        
        if ($stok_id) {
            $selectedStok = Stok::with(['jenisKentang', 'gudang'])
                ->where('id', $stok_id)
                ->whereHas('gudang', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->first();
        }

        // Get all konsumsi stocks (which are not set for sale, or they can be)
        // Here the user specifically mentioned "ambil stok yang siap dijual". 
        // We will just allow any stok with sufficient balance.
        $stoks = Stok::with(['jenisKentang', 'gudang'])
            ->whereHas('gudang', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereHas('jenisKentang', function($q) {
                $q->where('kategori', 'kentang_konsumsi');
            })
            ->where('stok_dijual', '>', 0)
            ->get();

        $koperasis = User::where('role', 'koperasi')->get();
        if ($koperasis->isEmpty()) {
            $koperasis = User::whereIn('role', ['admin', 'super admin'])->get();
        }

        // Ambil Harga Pasar dan Harga Petani sebagai referensi di javascript form
        $hargaPasars = \App\Models\HargaPasar::get()->keyBy('jenis_kentang_id');
        $hargaPetanis = \App\Models\Harga::where('user_id', Auth::id())->get()->keyBy('jenis_kentang_id');

        return view('petani.penawaran-panen.create', compact('stoks', 'koperasis', 'selectedStok', 'hargaPasars', 'hargaPetanis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'stok_id' => 'required|exists:stoks,id',
            'koperasi_id' => 'required|exists:users,id',
            'jumlah_kg' => 'required|numeric|min:0.01',
            'harga_tawaran_petani' => 'required|numeric|min:100',
        ]);

        $stok = Stok::where('id', $data['stok_id'])
            ->whereHas('gudang', function($q) {
                $q->where('user_id', Auth::id());
            })->firstOrFail();

        // Calculate maximum available for Penawaran.
        // We only require them to have physical stock that is marked as ready to sell (stok_dijual).
        if ($data['jumlah_kg'] > $stok->stok_dijual) {
            return back()->withErrors(['jumlah_kg' => 'Jumlah melebihi stok siap jual yang tersedia (Maks: ' . $stok->stok_dijual . ' Kg)'])->withInput();
        }

        $total_harga = $data['jumlah_kg'] * $data['harga_tawaran_petani'];

        PenawaranPanen::create([
            'petani_id' => Auth::id(),
            'koperasi_id' => $data['koperasi_id'],
            'jenis_kentang_id' => $stok->jenis_kentang_id,
            'gudang_id' => $stok->gudang_id,
            'jumlah_kg' => $data['jumlah_kg'],
            'harga_tawaran_petani' => $total_harga,
            'status' => 'menunggu'
        ]);

        return redirect()->route('petani.penawaran-panen.index')->with('success', 'Penawaran penjualan berhasil diajukan. Menunggu respon Koperasi.');
    }

    public function update(Request $request, string $id)
    {
        $penawaran = PenawaranPanen::where('petani_id', Auth::id())->findOrFail($id);
        
        $action = $request->input('action'); 
        
        if ($action === 'accept') {
            if ($penawaran->status !== 'dinegosiasi') {
                return back()->with('error', 'Penawaran tidak dalam status dinegosiasi.');
            }
            
            $penawaran->status = 'disetujui';
            $penawaran->save();

            $this->createPembelian($penawaran, $penawaran->harga_tawaran_koperasi);
            return back()->with('success', 'Penawaran disetujui! Transaksi telah dicatat ke Koperasi.');
            
        } elseif ($action === 'counter') {
            if ($penawaran->jumlah_tawar_petani >= 2) {
                return back()->with('error', 'Anda telah mencapai batas maksimal tawar-menawar (2 kali). Anda hanya bisa Sepakat atau Menolak tawaran terakhir Koperasi.');
            }
            
            $data = $request->validate([
                'harga_tawaran_petani' => 'required|numeric|min:100',
            ]);
            $penawaran->harga_tawaran_petani = $data['harga_tawaran_petani'];
            $penawaran->jumlah_tawar_petani += 1;
            $penawaran->status = 'menunggu'; // Goes back to koperasi
            $penawaran->save();
            return back()->with('success', 'Harga penawaran balasan berhasil dikirim.');
            
        } elseif ($action === 'reject') {
            $penawaran->status = 'ditolak';
            $penawaran->save();
            return back()->with('success', 'Penawaran ditolak.');
        }

        return back();
    }
    
    private function createPembelian(PenawaranPanen $penawaran, $hargaDeal)
    {
        \DB::transaction(function () use ($penawaran, $hargaDeal) {
            \App\Models\Pembelian::create([
                'petani_id' => $penawaran->petani_id,
                'koperasi_id' => $penawaran->koperasi_id,
                'jenis_kentang_id' => $penawaran->jenis_kentang_id,
                'jumlah_kg' => $penawaran->jumlah_kg,
                'total_harga' => $hargaDeal, // hargaDeal is already total
                'tanggal_pembelian' => now()->toDateString(),
                'status' => 'belum lunas'
            ]);

            $stok = Stok::where('gudang_id', $penawaran->gudang_id)
                ->where('jenis_kentang_id', $penawaran->jenis_kentang_id)
                ->first();
                
            if ($stok) {
                // If it was already allocated for sale, we deduct it
                $stok->jumlah_stok = max(0, $stok->jumlah_stok - $penawaran->jumlah_kg);
                // Also reduce stok_dijual if there was any overlap
                if ($stok->stok_dijual > $stok->jumlah_stok) {
                    $stok->stok_dijual = $stok->jumlah_stok;
                }
                $stok->save();
                $grade = $stok->grade ?? 'A';
            } else {
                $grade = 'A';
            }
            
            $gudangKoperasi = \App\Models\Gudang::firstOrCreate(
                ['jenis_gudang' => 'koperasi'],
                [
                    'nama_gudang' => 'Gudang Pusat Koperasi',
                    'alamat' => 'Alamat Koperasi Pusat',
                    'latitude' => 0.0,
                    'longitude' => 0.0,
                    'kapasitas_max' => 100000,
                    'status' => 'aktif'
                ]
            );

            $stokKoperasi = Stok::firstOrCreate(
                [
                    'gudang_id' => $gudangKoperasi->id,
                    'jenis_kentang_id' => $penawaran->jenis_kentang_id,
                    'grade' => $grade
                ],
                [
                    'jumlah_stok' => 0,
                    'stok_dijual' => 0,
                ]
            );

            $stokKoperasi->jumlah_stok += $penawaran->jumlah_kg;
            $stokKoperasi->stok_dijual += $penawaran->jumlah_kg;
            $stokKoperasi->save();
        });
    }
}
