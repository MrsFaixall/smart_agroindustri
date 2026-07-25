<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\MetodePembayaran;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Total stats
        $totalTransaksi = Pembelian::count();
        $totalLunas = Pembelian::where('status', 'lunas')->count();
        $totalPending = Pembelian::where('status', 'belum lunas')->count();
        $totalNilai = Pembelian::sum('total_harga');

        $user = Auth::user();

        // Query 1 & 2: Pembelian & Pembayaran
        $pembelianQuery = Pembelian::with(['petani', 'koperasi', 'jenisKentang', 'pembayarans']);
        $paymentQuery = Pembayaran::with(['pembelian.petani', 'pembelian.koperasi', 'metodePembayaran']);

        // Filter by role
        if ($user->role === 'petani') {
            $pembelianQuery->where('petani_id', $user->id);
            $paymentQuery->whereHas('pembelian', fn($q) => $q->where('petani_id', $user->id));
        } elseif ($user->role === 'koperasi') {
            $pembelianQuery->where('koperasi_id', $user->id);
            $paymentQuery->whereHas('pembelian', fn($q) => $q->where('koperasi_id', $user->id));
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $pembelianQuery->where(function($q) use ($search) {
                $q->whereHas('koperasi', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('petani', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('jenisKentang', function($qj) use ($search) {
                    $qj->where('nama_jenis', 'like', "%{$search}%");
                });
            });

            $paymentQuery->where(function($q) use ($search) {
                $q->whereHas('pembelian.koperasi', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('pembelian.petani', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('metodePembayaran', function($qm) use ($search) {
                    $qm->where('bank', 'like', "%{$search}%")->orWhere('kategori', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $pembelianQuery->whereDate('tanggal_pembelian', now()->toDateString());
                $paymentQuery->whereDate('tanggal_pembayaran', now()->toDateString());
            } elseif ($request->period === 'this_week') {
                $pembelianQuery->whereBetween('tanggal_pembelian', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
                $paymentQuery->whereBetween('tanggal_pembayaran', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            } elseif ($request->period === 'this_month') {
                $pembelianQuery->whereYear('tanggal_pembelian', now()->year)->whereMonth('tanggal_pembelian', now()->month);
                $paymentQuery->whereYear('tanggal_pembayaran', now()->year)->whereMonth('tanggal_pembayaran', now()->month);
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $pembelianQuery->whereBetween('tanggal_pembelian', [$request->start_date, $request->end_date]);
            $paymentQuery->whereBetween('tanggal_pembayaran', [$request->start_date, $request->end_date]);
        }

        $pembelians = $pembelianQuery->latest()->paginate(5, ['*'], 'pembelian_page')->withQueryString();
        $payments = $paymentQuery->latest()->paginate(5, ['*'], 'payment_page')->withQueryString();

        if ($user->role === 'petani' || $request->get('view') === 'petani') {
            return view('petani.pembayaran.index', compact('pembelians', 'payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
        } elseif ($user->role === 'mitra' || $request->get('view') === 'mitra') {
            return view('mitra.pembayaran.index', compact('pembelians', 'payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
        }

        return view('koperasi.pembayaran.index', compact('pembelians', 'payments', 'totalTransaksi', 'totalLunas', 'totalPending', 'totalNilai'));
    }

    public function create()
    {
        $pembelians = Pembelian::where('status', '!=', 'lunas')
            ->with(['petani', 'koperasi'])
            ->latest()
            ->get();

        if (request()->has('pembelian_id')) {
            $selected = Pembelian::with(['petani', 'koperasi'])->find(request('pembelian_id'));
            if ($selected && !$pembelians->contains('id', $selected->id)) {
                $pembelians->prepend($selected);
            }
        }

        $methods = MetodePembayaran::with('user')->latest()->get();
        $metodePembayarans = $methods;
        $midtransClientKey = config('midtrans.client_key');
        return view('koperasi.pembayaran.create', compact('pembelians', 'methods', 'metodePembayarans', 'midtransClientKey'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_pembayaran' => $request->input('tanggal_pembayaran', now()->toDateString()),
            'status' => $request->input('status', 'lunas'),
        ]);

        $data = $request->validate([
            'pembelian_id' => 'required|exists:pembelians,id',
            'metode_pembayaran_id' => 'nullable|exists:metode_pembayarans,id',
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'tanggal_pembayaran' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas,pending',
            'catatan' => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                Pembayaran::create($data);
                
                $pembelian = Pembelian::find($data['pembelian_id']);
                $totalDibayar = Pembayaran::where('pembelian_id', $pembelian->id)
                    ->whereIn('status', ['lunas', 'berhasil', 'sukses'])
                    ->sum('jumlah_bayar');

                if ($data['status'] === 'lunas' || $totalDibayar >= $pembelian->total_harga) {
                    $pembelian->update(['status' => 'lunas']);

                    try {
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
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Stock update warning on store: ' . $e->getMessage());
                    }
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

    public function invoice(string $id)
    {
        $payment = Pembayaran::with([
            'pembelian.petani',
            'pembelian.koperasi',
            'pembelian.jenisKentang',
            'metodePembayaran'
        ])->find($id);

        if (!$payment) {
            $payment = Pembayaran::with([
                'pembelian.petani',
                'pembelian.koperasi',
                'pembelian.jenisKentang',
                'metodePembayaran'
            ])->where('pembelian_id', $id)->first();
        }

        if (!$payment) {
            $pembelian = Pembelian::with(['petani', 'koperasi', 'jenisKentang'])->findOrFail($id);
            $payment = new Pembayaran([
                'id' => $pembelian->id,
                'pembelian_id' => $pembelian->id,
                'jumlah_bayar' => $pembelian->total_harga,
                'tanggal_pembayaran' => $pembelian->tanggal_pembelian,
                'status' => $pembelian->status,
            ]);
            $payment->setRelation('pembelian', $pembelian);
        }

        return view('koperasi.pembayaran.invoice', compact('payment'));
    }

    public function cetakStruk(string $id)
    {
        $payment = Pembayaran::with([
            'pembelian.petani',
            'pembelian.koperasi',
            'pembelian.jenisKentang',
            'metodePembayaran'
        ])->find($id);

        if (!$payment) {
            $payment = Pembayaran::with([
                'pembelian.petani',
                'pembelian.koperasi',
                'pembelian.jenisKentang',
                'metodePembayaran'
            ])->where('pembelian_id', $id)->first();
        }

        if (!$payment) {
            $pembelian = Pembelian::with(['petani', 'koperasi', 'jenisKentang'])->findOrFail($id);
            $payment = new Pembayaran([
                'id' => $pembelian->id,
                'pembelian_id' => $pembelian->id,
                'jumlah_bayar' => $pembelian->total_harga,
                'tanggal_pembayaran' => $pembelian->tanggal_pembelian,
                'status' => $pembelian->status,
            ]);
            $payment->setRelation('pembelian', $pembelian);
        }

        return view('koperasi.pembayaran.struk', compact('payment'));
    }
}
