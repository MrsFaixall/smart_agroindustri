<?php

namespace App\Http\Controllers;

use App\Models\PenawaranPanen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stok;

class PenawaranPanenKoperasiController extends Controller
{
    public function index()
    {
        $penawarans = PenawaranPanen::with(['jenisKentang', 'petani', 'gudang'])
            ->where('koperasi_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('koperasi.penawaran-panen.index', compact('penawarans'));
    }

    public function update(Request $request, string $id)
    {
        $penawaran = PenawaranPanen::where('koperasi_id', Auth::id())->findOrFail($id);
        
        $action = $request->input('action');
        
        if ($action === 'accept') {
            $penawaran->status = 'disetujui';
            $penawaran->save();

            // When Koperasi accepts, it's Petani's original price
            $this->createPembelian($penawaran, $penawaran->harga_tawaran_petani);

            return back()->with('success', 'Penawaran disetujui! Transaksi telah otomatis dicatat ke sistem Pembelian.');
            
        } elseif ($action === 'counter') {
            if ($penawaran->jumlah_tawar_koperasi >= 2) {
                return back()->with('error', 'Anda telah mencapai batas maksimal tawar-menawar (2 kali). Anda hanya bisa Menyetujui atau Menolak tawaran terakhir Petani.');
            }
            
            $data = $request->validate([
                'harga_tawaran_koperasi' => 'required|numeric|min:100',
            ]);
            $penawaran->harga_tawaran_koperasi = $data['harga_tawaran_koperasi'];
            $penawaran->jumlah_tawar_koperasi += 1;
            $penawaran->status = 'dinegosiasi';
            $penawaran->save();
            return back()->with('success', 'Harga penawaran (negosiasi) berhasil dikirim ke Petani.');
            
        } elseif ($action === 'reject') {
            $penawaran->status = 'ditolak';
            $penawaran->save();
            return back()->with('success', 'Penawaran telah ditolak.');
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
                $stok->jumlah_stok = max(0, $stok->jumlah_stok - $penawaran->jumlah_kg);
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
