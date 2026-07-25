<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanBuah;
use App\Models\User;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\Gudang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanBuahController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = PenjualanBuah::with(['pembeli', 'jenisKentang', 'koperasi']);

        if ($user->role === 'koperasi') {
            $query->where('koperasi_id', $user->id);
        }

        $transaksis = $query->latest()->paginate(10);
        $totalNilai = $query->sum('total_harga');

        return view('koperasi.penjualan-buah.index', compact('transaksis', 'totalNilai'));
    }

    public function create()
    {
        $pembelis = User::whereIn('role', ['mitra', 'konsumen'])->get();
        $jenisKentangs = JenisKentang::where('kategori', 'kentang_konsumsi')->get();

        // Ambil stok Koperasi yang tersedia
        $stokTersedia = [];
        $gudangKoperasi = Gudang::where('jenis_gudang', 'koperasi')->first();
        if ($gudangKoperasi) {
            $stoks = Stok::where('gudang_id', $gudangKoperasi->id)->get();
            foreach ($stoks as $s) {
                $stokTersedia[$s->jenis_kentang_id] = $s->stok_dijual;
            }
        }

        return view('koperasi.penjualan-buah.create', compact('pembelis', 'jenisKentangs', 'stokTersedia'));
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
        ]);

        $data['koperasi_id'] = Auth::user()->role === 'koperasi' ? Auth::id() : User::where('role', 'koperasi')->first()->id ?? 1;

        try {
            DB::transaction(function () use ($data) {
                $gudangKoperasi = Gudang::where('jenis_gudang', 'koperasi')->first();
                if (!$gudangKoperasi) {
                    throw new \Exception("Gudang Koperasi belum dibuat. Silakan lakukan Pembelian Hasil Panen terlebih dahulu.");
                }

                // Cek stok cukup
                $stokKoperasi = Stok::where('gudang_id', $gudangKoperasi->id)
                    ->where('jenis_kentang_id', $data['jenis_kentang_id'])
                    ->first();

                if (!$stokKoperasi || $stokKoperasi->stok_dijual < $data['jumlah_kg']) {
                    throw new \Exception("Stok hasil panen Koperasi tidak mencukupi untuk penjualan ini.");
                }

                // Potong stok koperasi
                $stokKoperasi->stok_dijual = max(0, $stokKoperasi->stok_dijual - $data['jumlah_kg']);
                $stokKoperasi->jumlah_stok = max(0, $stokKoperasi->jumlah_stok - $data['jumlah_kg']);
                $stokKoperasi->save();

                PenjualanBuah::create($data);
            });

            return redirect()->route('penjualan-buah.index')
                ->with('success', 'Transaksi Penjualan Panen berhasil disimpan dan stok telah diperbarui.');

        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_kg' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $transaksi = PenjualanBuah::findOrFail($id);

        try {
            DB::transaction(function () use ($transaksi) {
                $gudangKoperasi = Gudang::where('jenis_gudang', 'koperasi')->first();
                if ($gudangKoperasi) {
                    $stokKoperasi = Stok::where('gudang_id', $gudangKoperasi->id)
                        ->where('jenis_kentang_id', $transaksi->jenis_kentang_id)
                        ->first();
                        
                    if ($stokKoperasi) {
                        $stokKoperasi->jumlah_stok += $transaksi->jumlah_kg;
                        $stokKoperasi->stok_dijual += $transaksi->jumlah_kg;
                        $stokKoperasi->save();
                    }
                }
                $transaksi->delete();
            });

            return back()->with('success', 'Transaksi Penjualan Panen berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function bayar($id)
    {
        $transaksi = PenjualanBuah::findOrFail($id);
        $transaksi->update(['status' => 'lunas']);

        return back()->with('success', 'Transaksi berhasil dilunasi.');
    }
}
