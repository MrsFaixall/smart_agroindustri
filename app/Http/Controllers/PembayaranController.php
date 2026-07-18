<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\MetodePembayaran;
use App\Models\Stok;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $payments = Pembayaran::with(['pembelian.petani', 'metodePembayaran'])->latest()->get();
        return view('pengepul.pembayaran.index', compact('payments'));
    }

    public function create()
    {
        $pembelians = Pembelian::with(['petani', 'pengepul'])->latest()->get();
        $methods = MetodePembayaran::with('user')->latest()->get();
        $midtransClientKey = config('midtrans.client_key');
        return view('pengepul.pembayaran.create', compact('pembelians', 'methods', 'midtransClientKey'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pembelian_id' => 'required|exists:pembelians,id',
            'metode_pembayaran_id' => 'required|exists:metode_pembayarans,id',
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'tanggal_pembayaran' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas,pending',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                Pembayaran::create($data);
                
                $pembelian = Pembelian::find($data['pembelian_id']);
                // Hitung total pembayaran yang valid untuk pembelian ini
                $totalDibayar = Pembayaran::where('pembelian_id', $pembelian->id)
                    ->whereIn('status', ['lunas', 'berhasil', 'sukses', 'pending'])
                    ->sum('jumlah_bayar');

                if ($totalDibayar >= $pembelian->total_harga && $pembelian->status !== 'lunas') {
                    // Verifikasi stok sebelum melunasi
                    $totalStok = Stok::where('jenis_kentang_id', $pembelian->jenis_kentang_id)->sum('jumlah_stok');
                    if ($pembelian->jumlah_kg > $totalStok) {
                        throw new \Exception('Stok tidak mencukupi untuk melunasi transaksi pembelian ini. Sisa stok: ' . $totalStok);
                    }

                    // Kurangi stok FIFO
                    $jumlah_dibeli = $pembelian->jumlah_kg;
                    $stoks = Stok::where('jenis_kentang_id', $pembelian->jenis_kentang_id)
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

                    $pembelian->update(['status' => 'lunas']);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_bayar' => $e->getMessage()])->withInput();
        }

        return redirect()->route('pembayaran.index')->with('success', 'Transaksi pembayaran berhasil dibuat, status pembelian dan stok disesuaikan.');
    }

    public function show(string $id)
    {
        return redirect()->route('pembayaran.index');
    }
}
