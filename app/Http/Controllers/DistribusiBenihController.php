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
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DistribusiBenih::with(['petani', 'jenisKentang', 'koperasi']);

        if ($user->role === 'koperasi') {
            $query->where('koperasi_id', $user->id);
        } elseif ($user->role === 'petani') {
            $query->where('petani_id', $user->id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('jenisKentang', function($qk) use ($search) {
                    $qk->where('nama_jenis', 'like', "%{$search}%");
                })->orWhereHas('petani', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%");
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

        if ($user->role === 'petani') {
            return view('petani.distribusi-benih.index', compact('transaksis', 'totalNilai'));
        }
        return view('koperasi.distribusi-benih.index', compact('transaksis', 'totalNilai'));
    }

    public function create()
    {
        $petanis = User::where('role', 'petani')->get();
        $jenisKentangs = JenisKentang::whereHas('kategoriKentang', function($q) { $q->where('tipe_komoditas', 'benih'); })->get();

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

                // Tambah ke stok Petani (dapet benih)
                $gudangPetani = \App\Models\Gudang::where('user_id', $data['petani_id'])
                    ->where('jenis_gudang', 'petani')
                    ->first();

                if (!$gudangPetani) {
                    throw new \Exception("GUDANG_KOSONG");
                }

                $stokPetani = \App\Models\Stok::firstOrNew([
                    'gudang_id' => $gudangPetani->id,
                    'jenis_kentang_id' => $data['jenis_kentang_id'],
                    'grade' => 'A'
                ]);

                $stokPetani->jumlah_stok = ($stokPetani->jumlah_stok ?? 0) + $data['jumlah_kg'];
                $stokPetani->save();

                DistribusiBenih::create($data);
            });

            return redirect()->route('distribusi-benih.index')
                ->with('success', 'Transaksi Distribusi Benih berhasil disimpan dan stok telah diperbarui.');

        } catch (\Exception $e) {
            if ($e->getMessage() === 'GUDANG_KOSONG') {
                \App\Models\Notifikasi::create([
                    'user_id' => $data['petani_id'],
                    'pesan' => 'Koperasi ingin mendistribusikan benih kepada Anda, tetapi Anda belum memiliki gudang. Silakan buat gudang terlebih dahulu agar benih dapat disimpan.',
                    'tipe_notifikasi' => 'gudang_kosong',
                    'terkait_id' => null,
                    'url' => route('petani-gudang.create')
                ]);
                return back()->with('error', 'Gagal menyimpan distribusi: Petani belum memiliki gudang. Notifikasi telah dikirim ke petani untuk membuat gudang.')->withInput();
            }
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

    public function requestHasil($id)
    {
        $transaksi = DistribusiBenih::findOrFail($id);
        
        \App\Models\Notifikasi::create([
            'user_id' => $transaksi->petani_id,
            'pesan' => 'Koperasi meminta hasil panen dari benih yang telah didistribusikan (' . $transaksi->jenisKentang->nama_jenis . ' - ' . $transaksi->jumlah_kg . ' kg).',
            'tipe_notifikasi' => 'request_panen',
            'terkait_id' => $transaksi->id,
            'url' => route('panen.index')
        ]);

        return back()->with('success', 'Request hasil panen telah dikirim ke petani terkait.');
    }
}
