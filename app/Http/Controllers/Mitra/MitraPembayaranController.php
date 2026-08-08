<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\PembayaranPenjualan;
use App\Models\PenjualanBuah;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MitraPembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $query = PenjualanBuah::with(['koperasi', 'jenisKentang', 'pembayaranPenjualans'])
            ->where('pembeli_id', $user->id);
            
        $paymentQuery = PembayaranPenjualan::with(['penjualanBuah.koperasi', 'penjualanBuah.jenisKentang', 'metodePembayaran'])
            ->whereHas('penjualanBuah', fn($q) => $q->where('pembeli_id', $user->id));

        $totalTransaksi = (clone $query)->count();
        $totalLunas = (clone $query)->where('status', 'lunas')->count();
        $totalPending = (clone $query)->where('status', '!=', 'lunas')->count();
        $totalNilai = (clone $query)->sum('total_harga');

        $transaksis = $query->latest()->paginate(5, ['*'], 'penjualan_page')->withQueryString();
        $payments = $paymentQuery->latest()->paginate(5, ['*'], 'payment_page')->withQueryString();

        return view('mitra.pembayaran.index', compact('transaksis', 'payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
    }

    public function create()
    {
        $transaksis = PenjualanBuah::where('pembeli_id', Auth::id())
            ->where('status', '!=', 'lunas')
            ->with(['koperasi'])
            ->latest()
            ->get();

        if (request()->has('penjualan_id')) {
            $selected = PenjualanBuah::with(['koperasi'])->find(request('penjualan_id'));
            if ($selected && !$transaksis->contains('id', $selected->id)) {
                $transaksis->prepend($selected);
            }
        }

        $methods = MetodePembayaran::with('user')->latest()->get();
        $metodePembayarans = $methods;
        $midtransClientKey = config('midtrans.client_key');
        
        return view('mitra.pembayaran.create', compact('transaksis', 'methods', 'metodePembayarans', 'midtransClientKey'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_pembayaran' => $request->input('tanggal_pembayaran', now()->toDateString()),
            'status' => $request->input('status', 'lunas'),
        ]);

        $data = $request->validate([
            'penjualan_buah_id' => 'required|exists:penjualan_buahs,id',
            'metode_pembayaran_id' => 'nullable|exists:metode_pembayarans,id',
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'tanggal_pembayaran' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas,pending',
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($data) {
                PembayaranPenjualan::create($data);
                
                $penjualan = PenjualanBuah::find($data['penjualan_buah_id']);
                $totalDibayar = PembayaranPenjualan::where('penjualan_buah_id', $penjualan->id)
                    ->whereIn('status', ['lunas', 'berhasil', 'sukses'])
                    ->sum('jumlah_bayar');

                if ($data['status'] === 'lunas' || $totalDibayar >= $penjualan->total_harga) {
                    $penjualan->update(['status' => 'lunas']);
                    
                    // Transfer stok ke Gudang Mitra
                    PenjualanBuah::transferStockToMitra($penjualan);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_bayar' => $e->getMessage()])->withInput();
        }

        return redirect()->route('mitra.pembayaran.index')->with('success', 'Transaksi pembayaran berhasil dibuat.');
    }

    public function invoice($id)
    {
        $payment = PembayaranPenjualan::with([
            'penjualanBuah.pembeli',
            'penjualanBuah.koperasi',
            'penjualanBuah.jenisKentang',
            'metodePembayaran'
        ])->find($id);

        if (!$payment) {
            $payment = PembayaranPenjualan::with([
                'penjualanBuah.pembeli',
                'penjualanBuah.koperasi',
                'penjualanBuah.jenisKentang',
                'metodePembayaran'
            ])->where('penjualan_buah_id', $id)->first();
        }

        if (!$payment) {
            $penjualan = PenjualanBuah::with(['pembeli', 'koperasi', 'jenisKentang'])->findOrFail($id);
            $payment = new PembayaranPenjualan([
                'id' => $penjualan->id,
                'penjualan_buah_id' => $penjualan->id,
                'jumlah_bayar' => $penjualan->total_harga,
                'tanggal_pembayaran' => $penjualan->tanggal_transaksi,
                'status' => $penjualan->status,
            ]);
            $payment->setRelation('penjualanBuah', $penjualan);
        }

        return view('mitra.pembayaran.invoice', compact('payment'));
    }

    public function cetakStruk($id)
    {
        $payment = PembayaranPenjualan::with([
            'penjualanBuah.pembeli',
            'penjualanBuah.koperasi',
            'penjualanBuah.jenisKentang',
            'metodePembayaran'
        ])->find($id);

        if (!$payment) {
            $payment = PembayaranPenjualan::with([
                'penjualanBuah.pembeli',
                'penjualanBuah.koperasi',
                'penjualanBuah.jenisKentang',
                'metodePembayaran'
            ])->where('penjualan_buah_id', $id)->first();
        }

        if (!$payment) {
            $penjualan = PenjualanBuah::with(['pembeli', 'koperasi', 'jenisKentang'])->findOrFail($id);
            $payment = new PembayaranPenjualan([
                'id' => $penjualan->id,
                'penjualan_buah_id' => $penjualan->id,
                'jumlah_bayar' => $penjualan->total_harga,
                'tanggal_pembayaran' => $penjualan->tanggal_transaksi,
                'status' => $penjualan->status,
            ]);
            $payment->setRelation('penjualanBuah', $penjualan);
        }

        return view('mitra.pembayaran.struk', compact('payment'));
    }
}
