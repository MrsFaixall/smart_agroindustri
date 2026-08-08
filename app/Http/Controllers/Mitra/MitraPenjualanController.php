<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\PenjualanBuah;
use App\Models\User;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MitraPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PenjualanBuah::with(['pembeli', 'jenisKentang'])
            ->where('koperasi_id', $user->id); // Mitra bertindak sebagai penjual (koperasi_id)

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('jenisKentang', function($qk) use ($search) {
                    $qk->where('nama_jenis', 'like', "%{$search}%");
                })->orWhereHas('pembeli', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->input('end_date'));
        }

        $transaksis = $query->latest()->paginate(10)->withQueryString();
        $totalNilai = $query->sum('total_harga');

        return view('mitra.penjualan.index', compact('transaksis', 'totalNilai'));
    }

    public function create()
    {
        $pembelis = User::whereIn('role', ['konsumen', 'mitra'])->where('id', '!=', Auth::id())->get();
        $jenisKentangs = JenisKentang::all();

        // Ambil stok Mitra yang tersedia
        $stokTersedia = [];
        $gudangMitra = Gudang::where('user_id', Auth::id())->first();
        if ($gudangMitra) {
            $stoks = Stok::where('gudang_id', $gudangMitra->id)->get();
            foreach ($stoks as $s) {
                $stokTersedia[$s->jenis_kentang_id] = $s->stok_dijual;
            }
        }

        return view('mitra.penjualan.create', compact('pembelis', 'jenisKentangs', 'stokTersedia'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pembeli_id' => 'required|exists:users,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_kg' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:lunas,belum lunas',
            'grade' => 'required|string',
        ]);

        $data['koperasi_id'] = Auth::id(); // Mitra adalah penjual

        try {
            DB::transaction(function () use ($data) {
                $gudangMitra = Gudang::where('user_id', Auth::id())->first();
                if (!$gudangMitra) {
                    throw new \Exception("Gudang Mitra Anda belum terdaftar. Silakan hubungi admin.");
                }

                // Cek ketersediaan stok
                $stokMitra = Stok::where('gudang_id', $gudangMitra->id)
                    ->where('jenis_kentang_id', $data['jenis_kentang_id'])
                    ->first();

                if (!$stokMitra || $stokMitra->stok_dijual < $data['jumlah_kg']) {
                    throw new \Exception("Stok kentang di Gudang Mitra tidak mencukupi untuk penjualan ini.");
                }

                // Potong stok Mitra
                $stokMitra->stok_dijual = max(0, $stokMitra->stok_dijual - $data['jumlah_kg']);
                $stokMitra->jumlah_stok = max(0, $stokMitra->jumlah_stok - $data['jumlah_kg']);
                $stokMitra->save();

                PenjualanBuah::create($data);
            });

            return redirect()->route('mitra.penjualan.index')
                ->with('success', 'Transaksi Penjualan Kentang berhasil disimpan.');

        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_kg' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $transaksi = PenjualanBuah::where('koperasi_id', Auth::id())->findOrFail($id);

        try {
            DB::transaction(function () use ($transaksi) {
                $gudangMitra = Gudang::where('user_id', Auth::id())->first();
                if ($gudangMitra) {
                    $stokMitra = Stok::where('gudang_id', $gudangMitra->id)
                        ->where('jenis_kentang_id', $transaksi->jenis_kentang_id)
                        ->first();
                        
                    if ($stokMitra) {
                        $stokMitra->jumlah_stok += $transaksi->jumlah_kg;
                        $stokMitra->stok_dijual += $transaksi->jumlah_kg;
                        $stokMitra->save();
                    }
                }
                $transaksi->delete();
            });

            return back()->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function bayar($id)
    {
        $transaksi = PenjualanBuah::where('koperasi_id', Auth::id())->findOrFail($id);
        $transaksi->update(['status' => 'lunas']);

        return back()->with('success', 'Transaksi berhasil dilunasi.');
    }
}
