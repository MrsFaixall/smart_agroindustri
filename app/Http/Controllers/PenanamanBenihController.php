<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PenanamanBenih;
use App\Models\Gudang;
use App\Models\Stok;
use App\Models\JenisKentang;

class PenanamanBenihController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'petani') {
            abort(403);
        }

        $penanamans = PenanamanBenih::with(['gudang', 'jenisKentang', 'panen'])
            ->where('petani_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('petani.penanaman.index', compact('penanamans'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'petani') {
            abort(403);
        }

        // Get farmer's warehouses
        $gudangs = Gudang::where('user_id', $user->id)
            ->where('jenis_gudang', 'petani')
            ->get();

        // Get available seeds in their warehouses
        $availableSeeds = Stok::with(['jenisKentang', 'gudang'])
            ->whereIn('gudang_id', $gudangs->pluck('id'))
            ->whereHas('jenisKentang', function ($q) {
                $q->whereHas('kategoriKentang', function($q) { $q->where('tipe_komoditas', 'benih'); });
            })
            ->where('jumlah_stok', '>', 0)
            ->get();

        return view('petani.penanaman.create', compact('gudangs', 'availableSeeds'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_tanam_kg' => 'required|numeric|min:1',
            'tanggal_tanam' => 'required|date',
            'estimasi_hari' => 'required|integer|min:30', // User can input 100 days
        ]);

        $gudang = Gudang::where('id', $data['gudang_id'])->where('user_id', $user->id)->firstOrFail();

        try {
            DB::transaction(function () use ($data, $user, $gudang) {
                // Find stok for this seed
                $stok = Stok::where('gudang_id', $data['gudang_id'])
                    ->where('jenis_kentang_id', $data['jenis_kentang_id'])
                    ->first();

                if (!$stok || $stok->jumlah_stok < $data['jumlah_tanam_kg']) {
                    throw new \Exception('Stok bibit di gudang ini tidak mencukupi.');
                }

                // Deduct stock
                $stok->jumlah_stok -= $data['jumlah_tanam_kg'];
                
                // If it was offered for sale to Koperasi, also cap the stok_dijual
                if ($stok->stok_dijual > $stok->jumlah_stok) {
                    $stok->stok_dijual = $stok->jumlah_stok;
                }
                $stok->save();

                // If stock hits 0, maybe we can delete it, or just keep it 0. Keeping it 0 is fine.
                if ($stok->jumlah_stok == 0 && $stok->stok_dijual == 0) {
                    $stok->delete();
                }

                // Create Penanaman
                $estimasiPanen = \Carbon\Carbon::parse($data['tanggal_tanam'])->addDays((int) $data['estimasi_hari']);

                PenanamanBenih::create([
                    'petani_id' => $user->id,
                    'gudang_id' => $gudang->id,
                    'jenis_kentang_id' => $data['jenis_kentang_id'],
                    'jumlah_tanam_kg' => $data['jumlah_tanam_kg'],
                    'tanggal_tanam' => $data['tanggal_tanam'],
                    'estimasi_panen' => $estimasiPanen,
                    'status' => 'aktif',
                ]);
            });

            return redirect()->route('penanaman.index')->with('success', 'Berhasil mencatat penanaman benih. Stok benih di gudang telah dikurangi.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $penanaman = PenanamanBenih::findOrFail($id);
        if ($penanaman->petani_id !== Auth::id() || $penanaman->status !== 'aktif') {
            abort(403);
        }

        try {
            DB::transaction(function () use ($penanaman) {
                // Restore stock
                $stok = Stok::firstOrNew([
                    'gudang_id' => $penanaman->gudang_id,
                    'jenis_kentang_id' => $penanaman->jenis_kentang_id,
                    'grade' => 'A', // Defaults for seed
                ]);
                $stok->jumlah_stok += $penanaman->jumlah_tanam_kg;
                $stok->save();

                $penanaman->delete();
            });
            return back()->with('success', 'Catatan penanaman dibatalkan dan bibit dikembalikan ke gudang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan penanaman: ' . $e->getMessage());
        }
    }
}
