<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;

use App\Models\Pembelian;
use App\Models\User;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\MetodePembayaran;
use App\Models\Pembayaran;
use App\Models\Gudang;
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
        } elseif ($user->role === 'konsumen') {
            $query->where('petani_id', $user->id);
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

        $pembelians = $query->latest()->paginate(10)->withQueryString();

        return view('koperasi.pembelian.index', compact('pembelians', 'totalTransaksi', 'totalJumlah', 'totalNilai'));
    }

    public function create()
    {
        $petanis = User::whereIn('role', ['petani', 'konsumen'])->get();
        $koperasis = User::where('role', 'koperasi')->get();
        
        if ($koperasis->isEmpty()) {
            $koperasis = User::whereIn('role', ['admin', 'super admin'])->get();
        }

        $jenisKentangs = JenisKentang::with(['harga', 'stoks.gudang'])->get();
        
        $stok_per_petani = [];
        $semuaStok = Stok::with(['gudang', 'jenisKentang.harga'])->where('stok_dijual', '>', 0)->get();
        foreach ($semuaStok as $stok) {
            if (!$stok->gudang || !$stok->gudang->user_id) continue;
            $petani_id = $stok->gudang->user_id;
            $jenis_id = $stok->jenis_kentang_id;
            
            if (!isset($stok_per_petani[$petani_id])) {
                $stok_per_petani[$petani_id] = [];
            }
            if (!isset($stok_per_petani[$petani_id][$jenis_id])) {
                $stok_per_petani[$petani_id][$jenis_id] = [
                    'stok_dijual' => 0,
                    'nama_jenis' => $stok->jenisKentang->nama_jenis ?? '-',
                    'harga' => $stok->jenisKentang->harga->harga ?? 0
                ];
            }
            $stok_per_petani[$petani_id][$jenis_id]['stok_dijual'] += $stok->stok_dijual;
        }
        $stokPerPetaniJson = json_encode($stok_per_petani);

        $metodePembayarans = MetodePembayaran::with('user')->latest()->get();
        $metodePerPetani = [];
        foreach ($metodePembayarans as $metode) {
            if (!$metode->user_id) continue;
            if (!isset($metodePerPetani[$metode->user_id])) {
                $metodePerPetani[$metode->user_id] = [];
            }
            $metodePerPetani[$metode->user_id][] = [
                'id' => $metode->id,
                'bank' => $metode->bank,
                'no_rekening' => $metode->no_rekening,
                'atas_nama' => $metode->atas_nama
            ];
        }
        $metodePerPetaniJson = json_encode($metodePerPetani);

        return view('koperasi.pembelian.create', compact('petanis', 'koperasis', 'jenisKentangs', 'metodePembayarans', 'stokPerPetaniJson', 'metodePerPetaniJson'));
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

        // STRICT ALUR CEK STOK: Harus mengambil stok siap dijual (`stok_dijual`) di tabel stoks
        $totalStokSiapDijual = Stok::where('jenis_kentang_id', $data['jenis_kentang_id'])->whereHas('gudang', function($q) use ($data) { $q->where('user_id', $data['petani_id']); })->sum('stok_dijual');

        if ($totalStokSiapDijual <= 0) {
            return back()->withErrors([
                'jumlah_kg' => 'Pembelian tidak dapat dilakukan! Komoditas kentang ini belum diatur alokasi "Stok Siap Dijual"-nya di Manajemen Stok. Penjual/Petani harus mengatur Manajemen Stok terlebih dahulu.'
            ])->withInput();
        }

        if ($data['jumlah_kg'] > $totalStokSiapDijual) {
            $sisaText = number_format($totalStokSiapDijual, 0, ',', '.') . ' Kg';
            return back()->withErrors([
                'jumlah_kg' => "Jumlah pembelian melebihi alokasi stok siap dijual! Sisa stok yang siap dijual saat ini hanya {$sisaText}. Silakan sesuaikan atau tambahkan alokasi di Manajemen Stok."
            ])->withInput();
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
                
                // Transfer stock immediately since it is created as Lunas
                Pembelian::transferStock($pembelian);
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil dicatat dan stok siap dijual berhasil dipotong.');
    }

    public function show(string $id)
    {
        return redirect()->route('pembelian.index');
    }

    public function edit(string $id)
    {
        $pembelian = Pembelian::with('pembayarans')->findOrFail($id);
        $petanis = User::whereIn('role', ['petani', 'konsumen'])->get();
        $koperasis = User::where('role', 'koperasi')->get();

        if ($koperasis->isEmpty()) {
            $koperasis = User::whereIn('role', ['admin', 'super admin'])->get();
        }

        $jenisKentangs = JenisKentang::with(['harga', 'stoks.gudang'])->get();

        $stok_per_petani = [];
        $semuaStok = Stok::with(['gudang', 'jenisKentang.harga'])->where('stok_dijual', '>', 0)->get();
        
        // Include the current transaction's stock so it shows up in edit mode
        if ($pembelian) {
            $pet_id = $pembelian->petani_id;
            $jen_id = $pembelian->jenis_kentang_id;
            if (!isset($stok_per_petani[$pet_id])) $stok_per_petani[$pet_id] = [];
            if (!isset($stok_per_petani[$pet_id][$jen_id])) {
                $jks = JenisKentang::with('harga')->find($jen_id);
                $stok_per_petani[$pet_id][$jen_id] = [
                    'stok_dijual' => 0,
                    'nama_jenis' => $jks->nama_jenis ?? '-',
                    'harga' => $jks->harga->harga ?? 0
                ];
            }
            $stok_per_petani[$pet_id][$jen_id]['stok_dijual'] += $pembelian->jumlah_kg;
        }

        foreach ($semuaStok as $stok) {
            if (!$stok->gudang || !$stok->gudang->user_id) continue;
            $petani_id = $stok->gudang->user_id;
            $jenis_id = $stok->jenis_kentang_id;
            
            if (!isset($stok_per_petani[$petani_id])) $stok_per_petani[$petani_id] = [];
            if (!isset($stok_per_petani[$petani_id][$jenis_id])) {
                $stok_per_petani[$petani_id][$jenis_id] = [
                    'stok_dijual' => 0,
                    'nama_jenis' => $stok->jenisKentang->nama_jenis ?? '-',
                    'harga' => $stok->jenisKentang->harga->harga ?? 0
                ];
            }
            $stok_per_petani[$petani_id][$jenis_id]['stok_dijual'] += $stok->stok_dijual;
        }
        $stokPerPetaniJson = json_encode($stok_per_petani);

        $metodePembayarans = MetodePembayaran::with('user')->latest()->get();
        $metodePerPetani = [];
        foreach ($metodePembayarans as $metode) {
            if (!$metode->user_id) continue;
            if (!isset($metodePerPetani[$metode->user_id])) {
                $metodePerPetani[$metode->user_id] = [];
            }
            $metodePerPetani[$metode->user_id][] = [
                'id' => $metode->id,
                'bank' => $metode->bank,
                'no_rekening' => $metode->no_rekening,
                'atas_nama' => $metode->atas_nama
            ];
        }
        $metodePerPetaniJson = json_encode($metodePerPetani);

        return view('koperasi.pembelian.edit', compact('pembelian', 'petanis', 'koperasis', 'jenisKentangs', 'metodePembayarans', 'stokPerPetaniJson', 'metodePerPetaniJson'));
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
                $oldStatus = $pembelian->status;
                
                if ($oldStatus === 'lunas') {
                    // Reverse the stock transfer first using old attributes
                    Pembelian::reverseStockTransfer($pembelian);
                }

                // Update Pembelian
                $pembelian->update(array_diff_key($data, ['metode_pembayaran_id' => 1]));

                // Update or create Pembayaran
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
                    
                    // Transfer the stock now
                    Pembelian::transferStock($pembelian);
                } else {
                    // Delete manual pembayaran if changing to belum lunas
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
        return redirect()->route('pembelian.index')->withErrors(['error' => 'Transaksi pembelian yang telah terdaftar tidak dapat dihapus demi kepatuhan pencatatan/audit.']);
    }
}
