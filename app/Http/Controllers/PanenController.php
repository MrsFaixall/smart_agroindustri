<?php

namespace App\Http\Controllers;

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
        $query = Panen::with(['gudang', 'jenisKentang.harga', 'stok']);

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
        
        $totalMusimIni = Panen::sum('jumlah_kg');
        $hargaPasar = \App\Models\Harga::avg('harga') ?? 0;
        $menungguBayar = \App\Models\Pembelian::where('status', 'belum lunas')->sum('total_harga');
        
        $gudangs = Gudang::with('stoks')->get();
        $primaryGudang = $gudangs->first();
        
        $activeBatches = \App\Models\Stok::with(['jenisKentang', 'gudang', 'panen'])
            ->where('jumlah_stok', '>', 0)
            ->latest()
            ->paginate(5, ['*'], 'batch_page')
            ->withQueryString();

        return view('petani.panen.index', compact(
            'panens', 
            'totalMusimIni', 
            'hargaPasar', 
            'menungguBayar', 
            'gudangs',
            'primaryGudang',
            'activeBatches'
        ));
    }

    public function create()
    {
        $gudangs = Gudang::with('stoks')->get();
        $jenisKentangs = JenisKentang::all();
        return view('petani.panen.create', compact('gudangs', 'jenisKentangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'jumlah_kg' => 'required|numeric|gt:0',
            'tanggal_panen' => 'required|date',
            'grade' => 'required|string|in:A,B,C',
        ]);

        $gudang = Gudang::with('stoks')->findOrFail($data['gudang_id']);
        $terpakai = $gudang->kapasitas_terpakai;
        $sisaKapasitas = $gudang->kapasitas_max - $terpakai;

        if ($data['jumlah_kg'] > $sisaKapasitas) {
            $sisaText = $sisaKapasitas > 0 ? number_format($sisaKapasitas, 0, ',', '.') . ' Kg' : '0 Kg (Gudang Penuh)';
            return back()->withInput()->withErrors([
                'jumlah_kg' => "Kapasitas gudang '{$gudang->nama_gudang}' tidak mencukupi! Sisa kapasitas yang tersedia hanya {$sisaText}."
            ]);
        }

        DB::transaction(function () use ($data) {
            $panen = Panen::create($data);
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

        return redirect()->route('panen.index')->with('success', 'Data panen berhasil disimpan dan otomatis masuk ke gudang sebagai Stok Aktif.');
    }

    public function show(string $id)
    {
        return redirect()->route('panen.index');
    }

    public function edit(string $id)
    {
        $panen = Panen::findOrFail($id);
        $gudangs = Gudang::with('stoks')->get();
        $jenisKentangs = JenisKentang::all();

        return view('petani.panen.edit', compact('panen', 'gudangs', 'jenisKentangs'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'jumlah_kg' => 'required|numeric|gt:0',
            'tanggal_panen' => 'required|date',
            'grade' => 'required|string|in:A,B,C',
        ]);

        $panen = Panen::findOrFail($id);

        $gudang = Gudang::with('stoks')->findOrFail($data['gudang_id']);
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
        DB::transaction(function () use ($id) {
            $panen = Panen::findOrFail($id);
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
