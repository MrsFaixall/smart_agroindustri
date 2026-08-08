<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;

use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\Panen;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanenController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Panen::with(['gudang.user', 'jenisKentang.harga', 'stok']);

        // Filter panens by logged-in Petani's warehouses
        if ($user->role === 'petani') {
            $query->whereHas('gudang', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('jenis_gudang', 'petani');
            });
        }

        // Search filter (jenis kentang, gudang, or grade)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('jenisKentang', function($qj) use ($search) {
                    $qj->where('nama_jenis', 'like', "%{$search}%");
                })->orWhereHas('gudang', function($qg) use ($search) {
                    $qg->where('nama_gudang', 'like', "%{$search}%");
                })->orWhere('grade', 'like', "%{$search}%");
            });
        }

        // Period / date range filter
        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $query->whereDate('tanggal_panen', now()->toDateString());
            } elseif ($request->period === 'this_week') {
                $query->whereBetween('tanggal_panen', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            } elseif ($request->period === 'this_month') {
                $query->whereYear('tanggal_panen', now()->year)->whereMonth('tanggal_panen', now()->month);
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_panen', [$request->start_date, $request->end_date]);
        }

        $panens = $query->latest()->paginate(5, ['*'], 'panen_page')->withQueryString();
        
        $totalMusimIniQuery = Panen::query();
        if ($user->role === 'petani') {
            $totalMusimIniQuery->whereHas('gudang', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('jenis_gudang', 'petani');
            });
        }
        $totalMusimIni = $totalMusimIniQuery->sum('jumlah_kg');
        
        $hargaPasar = \App\Models\HargaPasar::avg('harga') ?? 0;
        
        $menungguBayarQuery = \App\Models\Pembelian::where('status', 'belum lunas');
        if ($user->role === 'petani') {
            $menungguBayarQuery->where('petani_id', $user->id);
        }
        $menungguBayar = $menungguBayarQuery->sum('total_harga');
        
        $gudangQuery = Gudang::where('jenis_gudang', 'petani');
        if ($user->role === 'petani') {
            $gudangQuery->where('user_id', $user->id);
        }
        $gudangs = $gudangQuery->with('stoks')->get();
        $primaryGudang = $gudangs->first();
        
        $batchQuery = Stok::with(['jenisKentang', 'gudang.user', 'panen'])
            ->where('jumlah_stok', '>', 0);
        if ($user->role === 'petani') {
            $batchQuery->whereHas('gudang', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('jenis_gudang', 'petani');
            });
        }
        $activeBatches = $batchQuery->latest()
            ->paginate(5, ['*'], 'batch_page')
            ->withQueryString();

        $hasNoGudang = false;
        if ($user->role === 'petani') {
            $hasNoGudang = $gudangs->isEmpty();
        }

        return view('petani.panen.index', compact(
            'panens', 
            'totalMusimIni', 
            'hargaPasar', 
            'menungguBayar', 
            'gudangs',
            'primaryGudang',
            'activeBatches',
            'hasNoGudang'
        ));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        $gudangQuery = Gudang::where('jenis_gudang', 'petani');
        if ($user->role === 'petani') {
            $gudangQuery->where('user_id', $user->id);
        }
        $gudangs = $gudangQuery->with(['stoks', 'user'])->get();
        
        if ($gudangs->isEmpty()) {
            return redirect()->route('panen.index')->with('error', 'Anda belum memiliki gudang. Silakan buat gudang terlebih dahulu sebelum mencatat hasil panen.');
        }

        $jenisKentangs = JenisKentang::all();
        $penanamanId = $request->query('penanaman_id');
        $penanaman = null;
        if ($penanamanId) {
            $penanaman = \App\Models\PenanamanBenih::with('jenisKentang')->find($penanamanId);
        }

        return view('petani.panen.create', compact('gudangs', 'jenisKentangs', 'penanaman'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'jumlah_kg' => 'required|numeric|min:0',
            'jumlah_busuk_kg' => 'nullable|numeric|min:0',
            'jumlah_gagal_kg' => 'nullable|numeric|min:0',
            'tanggal_panen' => 'required|date',
            'grade' => 'required|string|in:A,B,C',
            'penanaman_id' => 'nullable|exists:penanaman_benihs,id'
        ]);

        $gudang = Gudang::with('stoks')->findOrFail($data['gudang_id']);
        
        // Enforce ownership
        if ($user->role === 'petani' && ($gudang->user_id !== $user->id || $gudang->jenis_gudang !== 'petani')) {
            return back()->withInput()->withErrors([
                'gudang_id' => 'Gudang tujuan tidak valid untuk akun Anda.'
            ]);
        }

        $terpakai = $gudang->kapasitas_terpakai;
        $sisaKapasitas = $gudang->kapasitas_max - $terpakai;

        if ($data['jumlah_kg'] > $sisaKapasitas) {
            $sisaText = $sisaKapasitas > 0 ? number_format($sisaKapasitas, 0, ',', '.') . ' Kg' : '0 Kg (Gudang Penuh)';
            return back()->withInput()->withErrors([
                'jumlah_kg' => "Kapasitas gudang '{$gudang->nama_gudang}' tidak mencukupi! Sisa kapasitas yang tersedia hanya {$sisaText}."
            ]);
        }

        DB::transaction(function () use ($data) {
            $panen = Panen::create([
                'jenis_kentang_id' => $data['jenis_kentang_id'],
                'gudang_id' => $data['gudang_id'],
                'jumlah_kg' => $data['jumlah_kg'],
                'jumlah_busuk_kg' => $data['jumlah_busuk_kg'] ?? 0,
                'jumlah_gagal_kg' => $data['jumlah_gagal_kg'] ?? 0,
                'tanggal_panen' => $data['tanggal_panen'],
                'grade' => $data['grade'],
                'penanaman_id' => $data['penanaman_id'] ?? null
            ]);
            
            if ($data['jumlah_kg'] > 0) {
                Stok::updateOrCreate(
                    ['panen_id' => $panen->id],
                    [
                        'gudang_id' => $panen->gudang_id,
                        'jenis_kentang_id' => $panen->jenis_kentang_id,
                        'grade' => $panen->grade,
                        'jumlah_stok' => $panen->jumlah_kg,
                    ]
                );
            }

            if (!empty($data['penanaman_id'])) {
                $penanaman = \App\Models\PenanamanBenih::find($data['penanaman_id']);
                if ($penanaman) {
                    $penanaman->status = 'selesai';
                    $penanaman->save();
                }
            }
        });

        return redirect()->route('panen.index')->with('success', 'Data panen berhasil disimpan dan otomatis masuk ke gudang sebagai Stok Aktif.');
    }

    public function show(string $id)
    {
        return redirect()->route('panen.index');
    }

    public function edit(string $id)
    {
        $user = auth()->user();
        $panen = Panen::with('gudang')->findOrFail($id);
        
        // Enforce ownership
        if ($user->role === 'petani' && ($panen->gudang->user_id !== $user->id || $panen->gudang->jenis_gudang !== 'petani')) {
            abort(403, 'Aksi ini tidak diizinkan.');
        }

        $gudangQuery = Gudang::where('jenis_gudang', 'petani');
        if ($user->role === 'petani') {
            $gudangQuery->where('user_id', $user->id);
        }
        $gudangs = $gudangQuery->with(['stoks', 'user'])->get();
        $jenisKentangs = JenisKentang::all();

        return view('petani.panen.edit', compact('panen', 'gudangs', 'jenisKentangs'));
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $data = $request->validate([
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'jumlah_kg' => 'required|numeric|gt:0',
            'tanggal_panen' => 'required|date',
            'grade' => 'required|string|in:A,B,C',
        ]);

        $panen = Panen::with('gudang')->findOrFail($id);
        
        // Enforce ownership of the panen
        if ($user->role === 'petani' && ($panen->gudang->user_id !== $user->id || $panen->gudang->jenis_gudang !== 'petani')) {
            abort(403, 'Aksi ini tidak diizinkan.');
        }

        $gudang = Gudang::with('stoks')->findOrFail($data['gudang_id']);
        
        // Enforce ownership of the destination warehouse
        if ($user->role === 'petani' && ($gudang->user_id !== $user->id || $gudang->jenis_gudang !== 'petani')) {
            return back()->withInput()->withErrors([
                'gudang_id' => 'Gudang tujuan tidak valid untuk akun Anda.'
            ]);
        }

        $terpakai = $gudang->kapasitas_terpakai;
        if ($panen->gudang_id == $data['gudang_id']) {
            $terpakai -= $panen->jumlah_kg;
        }
        $sisaKapasitas = $gudang->kapasitas_max - $terpakai;

        if ($data['jumlah_kg'] > $sisaKapasitas) {
            $sisaText = $sisaKapasitas > 0 ? number_format($sisaKapasitas, 0, ',', '.') . ' Kg' : '0 Kg (Gudang Penuh)';
            return back()->withInput()->withErrors([
                'jumlah_kg' => "Kapasitas gudang '{$gudang->nama_gudang}' tidak mencukupi! Sisa kapasitas yang tersedia hanya {$sisaText}."
            ]);
        }

        DB::transaction(function () use ($data, $panen) {
            $panen->update($data);
            
            Stok::updateOrCreate(
                ['panen_id' => $panen->id],
                [
                    'gudang_id' => $panen->gudang_id,
                    'jenis_kentang_id' => $panen->jenis_kentang_id,
                    'grade' => $panen->grade,
                    'jumlah_stok' => $panen->jumlah_kg,
                ]
            );
        });

        return redirect()->route('panen.index')->with('success', 'Data panen berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $panen = Panen::with('gudang')->findOrFail($id);
        
        // Enforce ownership
        if ($user->role === 'petani' && ($panen->gudang->user_id !== $user->id || $panen->gudang->jenis_gudang !== 'petani')) {
            abort(403, 'Aksi ini tidak diizinkan.');
        }

        DB::transaction(function () use ($panen) {
            Stok::where('panen_id', $panen->id)->delete();
            $panen->delete();
        });
        return redirect()->route('panen.index')->with('success', 'Data panen berhasil dihapus.');
    }

    private function syncStokPanen(Panen $panen): void
    {
        Stok::updateOrCreate(
            ['panen_id' => $panen->id],
            [
                'gudang_id' => $panen->gudang_id,
                'jenis_kentang_id' => $panen->jenis_kentang_id,
                'jumlah_stok' => $panen->jumlah_kg,
                'grade' => $panen->grade,
            ]
        );
    }
}
