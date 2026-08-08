<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;

use App\Models\PembayaranDistribusi;
use App\Models\DistribusiBenih;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranDistribusiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = DistribusiBenih::with(['koperasi', 'petani', 'jenisKentang', 'pembayaranDistribusis']);
        $paymentQuery = \App\Models\PembayaranDistribusi::with(['distribusiBenih.petani', 'distribusiBenih.koperasi', 'distribusiBenih.jenisKentang', 'metodePembayaran']);
        
        if ($user->role === 'koperasi') {
            $query->where('koperasi_id', $user->id);
            $paymentQuery->whereHas('distribusiBenih', fn($q) => $q->where('koperasi_id', $user->id));
        } elseif ($user->role === 'petani') {
            $query->where('petani_id', $user->id);
            $paymentQuery->whereHas('distribusiBenih', fn($q) => $q->where('petani_id', $user->id));
        }

        $totalTransaksi = (clone $query)->count();
        $totalLunas = (clone $query)->where('status', 'lunas')->count();
        $totalPending = (clone $query)->where('status', '!=', 'lunas')->count();
        $totalNilai = (clone $query)->sum('total_harga');

        $transaksis = $query->latest()->paginate(5, ['*'], 'distribusi_page')->withQueryString();
        $payments = $paymentQuery->latest()->paginate(5, ['*'], 'payment_page')->withQueryString();

        if ($user->role === 'petani' || $request->get('view') === 'petani') {
            return view('petani.pembayaran.distribusi.index', compact('transaksis', 'payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
        }
        return view('koperasi.pembayaran.distribusi.index', compact('transaksis', 'payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
    }

    public function create()
    {
        $transaksis = DistribusiBenih::where('status', '!=', 'lunas')
            ->with(['petani', 'koperasi'])
            ->latest()
            ->get();

        if (request()->has('distribusi_id')) {
            $selected = DistribusiBenih::with(['petani', 'koperasi'])->find(request('distribusi_id'));
            if ($selected && !$transaksis->contains('id', $selected->id)) {
                $transaksis->prepend($selected);
            }
        }

        $methods = MetodePembayaran::with('user')->latest()->get();
        $metodePembayarans = $methods;
        $midtransClientKey = config('midtrans.client_key');
        return view('koperasi.pembayaran.distribusi.create', compact('transaksis', 'methods', 'metodePembayarans', 'midtransClientKey'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_pembayaran' => $request->input('tanggal_pembayaran', now()->toDateString()),
            'status' => $request->input('status', 'lunas'),
        ]);

        $data = $request->validate([
            'distribusi_benih_id' => 'required|exists:distribusi_benihs,id',
            'metode_pembayaran_id' => 'nullable|exists:metode_pembayarans,id',
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'tanggal_pembayaran' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas,pending',
            'catatan' => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                PembayaranDistribusi::create($data);
                
                $distribusi = DistribusiBenih::find($data['distribusi_benih_id']);
                $totalDibayar = PembayaranDistribusi::where('distribusi_benih_id', $distribusi->id)
                    ->whereIn('status', ['lunas', 'berhasil', 'sukses'])
                    ->sum('jumlah_bayar');

                if ($data['status'] === 'lunas' || $totalDibayar >= $distribusi->total_harga) {
                    $distribusi->update(['status' => 'lunas']);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_bayar' => $e->getMessage()])->withInput();
        }

        return redirect()->route('koperasi.pembayaran.distribusi')->with('success', 'Transaksi pembayaran berhasil dibuat.');
    }

    public function invoice(string $id)
    {
        $payment = PembayaranDistribusi::with([
            'distribusiBenih.petani',
            'distribusiBenih.koperasi',
            'distribusiBenih.jenisKentang',
            'metodePembayaran'
        ])->find($id);

        if (!$payment) {
            $payment = PembayaranDistribusi::with([
                'distribusiBenih.petani',
                'distribusiBenih.koperasi',
                'distribusiBenih.jenisKentang',
                'metodePembayaran'
            ])->where('distribusi_benih_id', $id)->first();
        }

        if (!$payment) {
            $distribusi = DistribusiBenih::with(['petani', 'koperasi', 'jenisKentang'])->findOrFail($id);
            $payment = new PembayaranDistribusi([
                'id' => $distribusi->id,
                'distribusi_benih_id' => $distribusi->id,
                'jumlah_bayar' => $distribusi->total_harga,
                'tanggal_pembayaran' => $distribusi->tanggal_distribusi,
                'status' => $distribusi->status,
            ]);
            $payment->setRelation('distribusiBenih', $distribusi);
        }

        return view('koperasi.pembayaran.distribusi.invoice', compact('payment'));
    }

    public function cetakStruk(string $id)
    {
        $payment = PembayaranDistribusi::with([
            'distribusiBenih.petani',
            'distribusiBenih.koperasi',
            'distribusiBenih.jenisKentang',
            'metodePembayaran'
        ])->find($id);

        if (!$payment) {
            $payment = PembayaranDistribusi::with([
                'distribusiBenih.petani',
                'distribusiBenih.koperasi',
                'distribusiBenih.jenisKentang',
                'metodePembayaran'
            ])->where('distribusi_benih_id', $id)->first();
        }

        if (!$payment) {
            $distribusi = DistribusiBenih::with(['petani', 'koperasi', 'jenisKentang'])->findOrFail($id);
            $payment = new PembayaranDistribusi([
                'id' => $distribusi->id,
                'distribusi_benih_id' => $distribusi->id,
                'jumlah_bayar' => $distribusi->total_harga,
                'tanggal_pembayaran' => $distribusi->tanggal_distribusi,
                'status' => $distribusi->status,
            ]);
            $payment->setRelation('distribusiBenih', $distribusi);
        }

        return view('koperasi.pembayaran.distribusi.struk', compact('payment'));
    }
}
