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
    public function index(Request $request)
    {
        $user = Auth::user();

        // Calculate total summary
        $totalTransaksi = Pembelian::count();
        $totalJumlah = Pembelian::sum('jumlah_kg');
        $totalNilai = Pembelian::sum('total_harga');

        $query = Pembelian::with(['petani', 'koperasi', 'jenisKentang']);

        // Filter by role
        if ($user->role === 'petani') {
            $query->where('petani_id', $user->id);
        } elseif ($user->role === 'koperasi') {
            $query->where('koperasi_id', $user->id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('koperasi', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('petani', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                })->orWhereHas('jenisKentang', function($qj) use ($search) {
                    $qj->where('nama_jenis', 'like', "%{$search}%");
                });
            });
        }

        // Period / date range filter
        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $query->whereDate('tanggal_pembelian', now()->toDateString());
            } elseif ($request->period === 'this_week') {
                $query->whereBetween('tanggal_pembelian', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            } elseif ($request->period === 'this_month') {
                $query->whereYear('tanggal_pembelian', now()->year)->whereMonth('tanggal_pembelian', now()->month);
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pembelian', [$request->start_date, $request->end_date]);
        }

        $pembelians = $query->latest()->paginate(5)->withQueryString();

        return view('koperasi.pembelian.index', compact('pembelians', 'totalTransaksi', 'totalJumlah', 'totalNilai'));
    }

    public function create()
    {
        $petanis = User::where('role', 'petani')->get();
        $koperasis = User::where('role', 'koperasi')->get();
        $jenisKentangs = JenisKentang::with(['harga', 'stoks.gudang'])->get()->map(function($jenis) {
            $jenis->total_stok = $jenis->stoks->sum('jumlah_stok');
            $jenis->harga_per_kg = $jenis->harga ? $jenis->harga->harga : 0;
            
            $gudangStoks = $jenis->stoks->filter(fn($s) => $s->jumlah_stok > 0)->groupBy('gudang_id')->map(function($stoks) {
                $gudangName = $stoks->first()->gudang->nama_gudang ?? 'Gudang Utama';
                $subtotal = $stoks->sum('jumlah_stok');
                return "{$gudangName}: {$subtotal} Kg";
            })->values()->implode(', ');

            $jenis->gudang_info = $gudangStoks ? $gudangStoks : 'Stok Kosong';
            return $jenis;
        });
        $metodePembayarans = MetodePembayaran::with('user')->latest()->get();
        return view('koperasi.pembelian.create', compact('petanis', 'koperasis', 'jenisKentangs', 'metodePembayarans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'petani_id' => 'required|exists:users,id',
            'koperasi_id' => 'required|exists:users,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_kg' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0.01',
            'tanggal_pembelian' => 'required|date',
            'status' => 'required|string|in:lunas,belum lunas',
            'metode_pembayaran_id' => 'required_if:status,lunas|nullable|exists:metode_pembayarans,id',
        ]);
        if ($data['status'] === 'lunas') {
            $totalStokSiapDijual = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->sum('stok_dijual');
            if ($totalStokSiapDijual <= 0) {
                $totalStokSiapDijual = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->sum('jumlah_stok');
            }
            if ($data['jumlah_kg'] > $totalStokSiapDijual) {
                return back()->withErrors(['jumlah_kg' => 'Stok yang siap dijual tidak mencukupi! Sisa stok siap dijual: ' . number_format($totalStokSiapDijual, 0, ',', '.') . ' Kg'])->withInput();
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
                    ->where('stok_dijual', '>', 0)
                    ->orderBy('id')
                    ->get();
                    
                if ($stoks->sum('stok_dijual') < $jumlah_dibeli) {
                    $stoks = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])
                        ->where('jumlah_stok', '>', 0)
                        ->orderBy('id')
                        ->get();
                }

                foreach ($stoks as $stok) {
                    if ($jumlah_dibeli <= 0) break;
                    
                    $kurangi = min($jumlah_dibeli, max($stok->stok_dijual, $stok->jumlah_stok));
                    $stok->jumlah_stok = max(0, $stok->jumlah_stok - $kurangi);
                    $stok->stok_dijual = max(0, ($stok->stok_dijual ?? 0) - $kurangi);
                    $stok->save();
                    
                    $jumlah_dibeli -= $kurangi;
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
        $koperasis = User::where('role', 'koperasi')->get();
        $jenisKentangs = JenisKentang::with(['harga', 'stoks.gudang'])->get()->map(function($jenis) {
            $jenis->total_stok = $jenis->stoks->sum('stok_dijual') > 0 ? $jenis->stoks->sum('stok_dijual') : $jenis->stoks->sum('jumlah_stok');
            $jenis->harga_per_kg = $jenis->harga ? $jenis->harga->harga : 0;
            
            $gudangStoks = $jenis->stoks->filter(fn($s) => $s->jumlah_stok > 0)->groupBy('gudang_id')->map(function($stoks) {
                $gudangName = $stoks->first()->gudang->nama_gudang ?? 'Gudang Utama';
                $subtotal = $stoks->sum('stok_dijual') > 0 ? $stoks->sum('stok_dijual') : $stoks->sum('jumlah_stok');
                return "{$gudangName}: {$subtotal} Kg";
            })->values()->implode(', ');

            $jenis->gudang_info = $gudangStoks ? $gudangStoks : 'Stok Kosong';
            return $jenis;
        });
        $metodePembayarans = MetodePembayaran::with('user')->latest()->get();
        return view('koperasi.pembelian.edit', compact('pembelian', 'petanis', 'koperasis', 'jenisKentangs', 'metodePembayarans'));
    }

    public function update(Request $request, string $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $data = $request->validate([
            'petani_id' => 'required|exists:users,id',
            'koperasi_id' => 'required|exists:users,id',
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
                        $stokToReturn->stok_dijual = ($stokToReturn->stok_dijual ?? 0) + $old_jumlah;
                        $stokToReturn->save();
                    }
                }

                // Cek stok baru jika status baru lunas
                if ($data['status'] === 'lunas') {
                    $totalStokSiapDijual = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->sum('stok_dijual');
                    if ($totalStokSiapDijual <= 0) {
                        $totalStokSiapDijual = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->sum('jumlah_stok');
                    }
                    if ($data['jumlah_kg'] > $totalStokSiapDijual) {
                        throw new \Exception('Stok yang siap dijual tidak mencukupi! Sisa stok siap dijual: ' . number_format($totalStokSiapDijual, 0, ',', '.') . ' Kg');
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
                        ->where('stok_dijual', '>', 0)
                        ->orderBy('id')
                        ->get();
                        
                    if ($stoks->sum('stok_dijual') < $jumlah_dibeli) {
                        $stoks = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])
                            ->where('jumlah_stok', '>', 0)
                            ->orderBy('id')
                            ->get();
                    }

                    foreach ($stoks as $stok) {
                        if ($jumlah_dibeli <= 0) break;
                        
                        $kurangi = min($jumlah_dibeli, max($stok->stok_dijual, $stok->jumlah_stok));
                        $stok->jumlah_stok = max(0, $stok->jumlah_stok - $kurangi);
                        $stok->stok_dijual = max(0, ($stok->stok_dijual ?? 0) - $kurangi);
                        $stok->save();
                        
                        $jumlah_dibeli -= $kurangi;
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
