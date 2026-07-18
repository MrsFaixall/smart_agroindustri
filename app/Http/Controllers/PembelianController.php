<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\User;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\MetodePembayaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with(['petani', 'pengepul', 'jenisKentang'])->latest()->get();
        
        // Calculate summary
        $totalTransaksi = $pembelians->count();
        $totalJumlah = $pembelians->sum('jumlah_kg');
        $totalNilai = $pembelians->sum('total_harga');

        return view('pengepul.pembelian.index', compact('pembelians', 'totalTransaksi', 'totalJumlah', 'totalNilai'));
    }

    public function create()
    {
        $petanis = User::where('role', 'petani')->get();
        $pengepuls = User::where('role', 'pengepul')->get();
        $jenisKentangs = JenisKentang::with(['harga', 'stoks'])->get()->map(function($jenis) {
            $jenis->total_stok = $jenis->stoks->sum('jumlah_stok');
            $jenis->harga_per_kg = $jenis->harga ? $jenis->harga->harga : 0;
            return $jenis;
        });
        $metodePembayarans = MetodePembayaran::with('user')->latest()->get();
        return view('pengepul.pembelian.create', compact('petanis', 'pengepuls', 'jenisKentangs', 'metodePembayarans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'petani_id' => 'required|exists:users,id',
            'pengepul_id' => 'required|exists:users,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_kg' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0.01',
            'tanggal_pembelian' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas',
            'metode_pembayaran_id' => 'required_if:status,lunas|nullable|exists:metode_pembayarans,id',
        ]);
        if ($data['status'] === 'lunas') {
            $totalStok = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->sum('jumlah_stok');
            if ($data['jumlah_kg'] > $totalStok) {
                return back()->withErrors(['jumlah_kg' => 'Stok tidak mencukupi, sisa stok: ' . $totalStok])->withInput();
            }
        }

        DB::transaction(function () use ($data) {
            $pembelian = Pembelian::create(array_diff_key($data, ['metode_pembayaran_id' => 1]));
            
            if ($data['status'] === 'lunas') {
                if (!empty($data['metode_pembayaran_id'])) {
                    Pembayaran::create([
                        'pembelian_id' => $pembelian->id,
                        'metode_pembayaran_id' => $data['metode_pembayaran_id'],
                        'jumlah_bayar' => $data['total_harga'],
                        'tanggal_pembayaran' => $data['tanggal_pembelian'],
                        'status' => 'lunas',
                    ]);
                }
                
                $jumlah_dibeli = $data['jumlah_kg'];
                $stoks = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])
                    ->where('jumlah_stok', '>', 0)
                    ->orderBy('id')
                    ->get();
                    
                foreach ($stoks as $stok) {
                    if ($jumlah_dibeli <= 0) break;
                    
                    if ($stok->jumlah_stok >= $jumlah_dibeli) {
                        $stok->jumlah_stok -= $jumlah_dibeli;
                        $stok->save();
                        $jumlah_dibeli = 0;
                    } else {
                        $jumlah_dibeli -= $stok->jumlah_stok;
                        $stok->jumlah_stok = 0;
                        $stok->save();
                    }
                }
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil dicatat.');
    }

    public function show(string $id)
    {
        return redirect()->route('pembelian.index');
    }

    public function edit(string $id)
    {
        $pembelian = Pembelian::with('pembayarans')->findOrFail($id);
        $petanis = User::where('role', 'petani')->get();
        $pengepuls = User::where('role', 'pengepul')->get();
        $jenisKentangs = JenisKentang::with(['harga', 'stoks'])->get()->map(function($jenis) {
            $jenis->total_stok = $jenis->stoks->sum('jumlah_stok');
            $jenis->harga_per_kg = $jenis->harga ? $jenis->harga->harga : 0;
            return $jenis;
        });
        $metodePembayarans = MetodePembayaran::with('user')->latest()->get();
        return view('pengepul.pembelian.edit', compact('pembelian', 'petanis', 'pengepuls', 'jenisKentangs', 'metodePembayarans'));
    }

    public function update(Request $request, string $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $data = $request->validate([
            'petani_id' => 'required|exists:users,id',
            'pengepul_id' => 'required|exists:users,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_kg' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0.01',
            'tanggal_pembelian' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas',
            'metode_pembayaran_id' => 'required_if:status,lunas|nullable|exists:metode_pembayarans,id',
        ]);

        try {
            DB::transaction(function () use ($pembelian, $data) {
                $old_status = $pembelian->status;
                $old_jumlah = $pembelian->jumlah_kg;
                $old_jenis = $pembelian->jenis_kentang_id;
                
                // Kembalikan stok lama jika sebelumnya lunas
                if ($old_status === 'lunas') {
                    $stokToReturn = Stok::where('jenis_kentang_id', $old_jenis)->first();
                    if ($stokToReturn) {
                        $stokToReturn->jumlah_stok += $old_jumlah;
                        $stokToReturn->save();
                    }
                }

                // Cek stok baru jika status baru lunas
                if ($data['status'] === 'lunas') {
                    $totalStok = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->sum('jumlah_stok');
                    if ($data['jumlah_kg'] > $totalStok) {
                        throw new \Exception('Stok tidak mencukupi, sisa stok: ' . $totalStok);
                    }
                }

                $pembelian->update(array_diff_key($data, ['metode_pembayaran_id' => 1]));

                if ($data['status'] === 'lunas') {
                    if (!empty($data['metode_pembayaran_id'])) {
                        Pembayaran::updateOrCreate(
                            ['pembelian_id' => $pembelian->id],
                            [
                                'metode_pembayaran_id' => $data['metode_pembayaran_id'],
                                'jumlah_bayar' => $data['total_harga'],
                                'tanggal_pembayaran' => $data['tanggal_pembelian'],
                                'status' => 'lunas',
                            ]
                        );
                    }

                    // Kurangi stok baru
                    $jumlah_dibeli = $data['jumlah_kg'];
                    $stoks = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])
                        ->where('jumlah_stok', '>', 0)
                        ->orderBy('id')
                        ->get();
                        
                    foreach ($stoks as $stok) {
                        if ($jumlah_dibeli <= 0) break;
                        
                        if ($stok->jumlah_stok >= $jumlah_dibeli) {
                            $stok->jumlah_stok -= $jumlah_dibeli;
                            $stok->save();
                            $jumlah_dibeli = 0;
                        } else {
                            $jumlah_dibeli -= $stok->jumlah_stok;
                            $stok->jumlah_stok = 0;
                            $stok->save();
                        }
                    }
                } else if ($data['status'] === 'belum lunas') {
                    Pembayaran::where('pembelian_id', $pembelian->id)->delete();
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_kg' => $e->getMessage()])->withInput();
        }

        return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        DB::transaction(function () use ($pembelian) {
            if ($pembelian->status === 'lunas') {
                $stokToReturn = Stok::where('jenis_kentang_id', $pembelian->jenis_kentang_id)->first();
                if ($stokToReturn) {
                    $stokToReturn->jumlah_stok += $pembelian->jumlah_kg;
                    $stokToReturn->save();
                }
            }
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil dihapus.');
    }
}
