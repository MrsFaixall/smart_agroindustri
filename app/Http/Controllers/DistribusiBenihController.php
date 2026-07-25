<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistribusiBenih;
use App\Models\User;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\Gudang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistribusiBenihController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = DistribusiBenih::with(['petani', 'jenisKentang', 'koperasi']);

        if ($user->role === 'koperasi') {
            $query->where('koperasi_id', $user->id);
        }

        $transaksis = $query->latest()->paginate(10);
        $totalNilai = $query->sum('total_harga');

        return view('koperasi.distribusi-benih.index', compact('transaksis', 'totalNilai'));
    }

    public function create()
    {
        $petanis = User::where('role', 'petani')->get();
        $jenisKentangs = JenisKentang::where('kategori', 'benih')->get();

        // Ambil stok Koperasi yang tersedia
        $stokTersedia = [];
        $gudangKoperasi = Gudang::where('jenis_gudang', 'koperasi')->first();
        if ($gudangKoperasi) {
            $stoks = Stok::where('gudang_id', $gudangKoperasi->id)->get();
            foreach ($stoks as $s) {
                $stokTersedia[$s->jenis_kentang_id] = $s->stok_dijual;
            }
        }

        return view('koperasi.distribusi-benih.create', compact('petanis', 'jenisKentangs', 'stokTersedia'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'petani_id' => 'required|exists:users,id',
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
                    throw new \Exception("Gudang Koperasi belum dibuat. Silakan lakukan Pengadaan Benih terlebih dahulu.");
                }

                // Cek stok cukup
                $stokKoperasi = Stok::where('gudang_id', $gudangKoperasi->id)
                    ->where('jenis_kentang_id', $data['jenis_kentang_id'])
                    ->first();

                if (!$stokKoperasi || $stokKoperasi->stok_dijual < $data['jumlah_kg']) {
                    throw new \Exception("Stok Koperasi tidak mencukupi untuk distribusi ini.");
                }

                // Potong stok koperasi
                $stokKoperasi->stok_dijual = max(0, $stokKoperasi->stok_dijual - $data['jumlah_kg']);
                $stokKoperasi->jumlah_stok = max(0, $stokKoperasi->jumlah_stok - $data['jumlah_kg']);
                $stokKoperasi->save();

                DistribusiBenih::create($data);
            });

            return redirect()->route('distribusi-benih.index')
                ->with('success', 'Transaksi Distribusi Benih berhasil disimpan dan stok telah diperbarui.');

        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_kg' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $transaksi = DistribusiBenih::findOrFail($id);

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

            return back()->with('success', 'Transaksi Distribusi Benih berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function bayar($id)
    {
        $transaksi = DistribusiBenih::findOrFail($id);
        $transaksi->update(['status' => 'lunas']);

        return back()->with('success', 'Transaksi berhasil dilunasi.');
    }
}
