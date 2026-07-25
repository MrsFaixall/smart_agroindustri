<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengadaanBenih;
use App\Models\User;
use App\Models\JenisKentang;
use App\Models\Stok;
use App\Models\Gudang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengadaanBenihController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PengadaanBenih::with(['mitra', 'jenisKentang', 'koperasi']);

        if ($user->role === 'koperasi') {
            $query->where('koperasi_id', $user->id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('jenisKentang', function($qk) use ($search) {
                    $qk->where('nama_jenis', 'like', "%{$search}%");
                })->orWhereHas('mitra', function($qm) use ($search) {
                    $qm->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->input('end_date'));
        }

        $transaksis = $query->latest()->paginate(10)->withQueryString();
        $totalNilai = $query->sum('total_harga');

        return view('koperasi.pengadaan-benih.index', compact('transaksis', 'totalNilai'));
    }

    public function create()
    {
        $mitras = User::where('role', 'mitra')->get();
        $jenisKentangs = JenisKentang::where('kategori', 'benih_hulu')->get();

        return view('koperasi.pengadaan-benih.create', compact('mitras', 'jenisKentangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mitra_id' => 'required|exists:users,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_kg' => 'required|numeric|min:0.01',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:lunas,belum lunas',
        ]);

        $data['koperasi_id'] = Auth::user()->role === 'koperasi' ? Auth::id() : User::where('role', 'koperasi')->first()->id ?? 1;

        try {
            DB::transaction(function () use ($data) {
                $gudangKoperasi = Gudang::firstOrCreate(
                    ['jenis_gudang' => 'koperasi'],
                    [
                        'nama_gudang' => 'Gudang Pusat Koperasi',
                        'alamat' => 'Alamat Koperasi Pusat',
                        'latitude' => 0.0,
                        'longitude' => 0.0,
                        'kapasitas_max' => 100000,
                        'status' => 'aktif'
                    ]
                );

                // Tambah stok koperasi
                $stokKoperasi = Stok::firstOrCreate(
                    [
                        'gudang_id' => $gudangKoperasi->id,
                        'jenis_kentang_id' => $data['jenis_kentang_id'],
                        'grade' => 'A'
                    ],
                    [
                        'jumlah_stok' => 0,
                        'stok_dijual' => 0,
                    ]
                );

                $stokKoperasi->jumlah_stok += $data['jumlah_kg'];
                $stokKoperasi->stok_dijual += $data['jumlah_kg'];
                $stokKoperasi->save();

                PengadaanBenih::create($data);
            });

            return redirect()->route('pengadaan-benih.index')
                ->with('success', 'Transaksi Pengadaan Benih berhasil disimpan dan stok telah diperbarui.');

        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_kg' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $transaksi = PengadaanBenih::findOrFail($id);

        try {
            DB::transaction(function () use ($transaksi) {
                $gudangKoperasi = Gudang::where('jenis_gudang', 'koperasi')->first();
                if ($gudangKoperasi) {
                    $stokKoperasi = Stok::where('gudang_id', $gudangKoperasi->id)
                        ->where('jenis_kentang_id', $transaksi->jenis_kentang_id)
                        ->first();
                        
                    if ($stokKoperasi) {
                        $stokKoperasi->jumlah_stok = max(0, $stokKoperasi->jumlah_stok - $transaksi->jumlah_kg);
                        $stokKoperasi->stok_dijual = max(0, $stokKoperasi->stok_dijual - $transaksi->jumlah_kg);
                        $stokKoperasi->save();
                    }
                }
                $transaksi->delete();
            });

            return back()->with('success', 'Transaksi Pengadaan Benih berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function bayar($id)
    {
        $transaksi = PengadaanBenih::findOrFail($id);
        $transaksi->update(['status' => 'lunas']);

        return back()->with('success', 'Transaksi berhasil dilunasi.');
    }
}
