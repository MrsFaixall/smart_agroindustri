<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\PengajuanBenih;
use App\Models\JenisKentang;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class PengajuanBenihController extends Controller
{
    public function indexPetani()
    {
        $user = Auth::user();
        if ($user->role === 'petani' || in_array($user->role, ['admin', 'super admin', 'superadmin'])) {
            $query = PengajuanBenih::with(['koperasi', 'jenisKentang']);
            if ($user->role === 'petani') {
                $query->where('petani_id', $user->id);
            }
            $pengajuans = $query->latest()->paginate(10);
            return view('petani.pengajuan-benih.index', compact('pengajuans'));
        }
        abort(403);
    }

    public function indexKoperasi()
    {
        $user = Auth::user();
        if ($user->role === 'koperasi' || in_array($user->role, ['admin', 'super admin', 'superadmin'])) {
            $query = PengajuanBenih::with(['petani', 'jenisKentang']);
            if ($user->role === 'koperasi') {
                $query->where('koperasi_id', $user->id);
            }
            $pengajuans = $query->latest()->paginate(10);
            return view('koperasi.pengajuan-benih.index', compact('pengajuans'));
        }
        abort(403);
    }

    public function create()
    {
        $petaniGudang = \App\Models\Gudang::where('user_id', Auth::id())->where('jenis_gudang', 'petani')->first();
        if (!$petaniGudang) {
            return redirect()->route('petani-gudang.create')->with('error', 'Anda belum memiliki gudang. Silakan buat gudang terlebih dahulu sebelum mengajukan benih.');
        }

        $koperasis = User::where('role', 'koperasi')->get();
        $jenisKentangs = JenisKentang::whereHas('kategoriKentang', function($q) { $q->where('tipe_komoditas', 'benih'); })->get();
        return view('petani.pengajuan-benih.create', compact('koperasis', 'jenisKentangs'));
    }

    public function store(Request $request)
    {
        $petaniGudang = \App\Models\Gudang::where('user_id', Auth::id())->where('jenis_gudang', 'petani')->first();
        if (!$petaniGudang) {
            \App\Models\Notifikasi::create([
                'user_id' => Auth::id(),
                'pesan' => 'Anda belum memiliki gudang. Silakan buat gudang terlebih dahulu sebelum mengajukan benih.',
                'tipe_notifikasi' => 'gudang_kosong',
                'url' => route('petani-gudang.create')
            ]);
            return redirect()->route('petani-gudang.create')->with('error', 'Anda belum memiliki gudang. Silakan buat gudang terlebih dahulu sebelum mengajukan benih.');
        }

        $request->validate([
            'koperasi_id' => 'required|exists:users,id',
            'jenis_kentang_id' => 'required|exists:jenis_kentangs,id',
            'jumlah_kg' => 'required|numeric|min:1',
            'tipe_pengajuan' => 'required|in:meminta,membeli',
        ]);

        $pengajuan = PengajuanBenih::create([
            'petani_id' => Auth::id(),
            'koperasi_id' => $request->koperasi_id,
            'jenis_kentang_id' => $request->jenis_kentang_id,
            'jumlah_kg' => $request->jumlah_kg,
            'tipe_pengajuan' => $request->tipe_pengajuan,
            'status' => 'pending',
            'tanggal_pengajuan' => now()->toDateString(),
        ]);

        $tipe = $request->tipe_pengajuan == 'meminta' ? 'meminta bantuan' : 'membeli';

        // Buat notifikasi untuk koperasi
        Notifikasi::create([
            'user_id' => $request->koperasi_id,
            'pesan' => 'Ada pengajuan (' . $tipe . ') benih baru dari ' . Auth::user()->name . ' sejumlah ' . $request->jumlah_kg . ' kg.',
            'tipe_notifikasi' => 'pengajuan_benih',
            'terkait_id' => $pengajuan->id,
            'url' => route('pengajuan-benih.koperasi')
        ]);

        return redirect()->route('pengajuan-benih.petani')->with('success', 'Pengajuan benih berhasil dikirim.');
    }

    public function approve($id)
    {
        $pengajuan = PengajuanBenih::findOrFail($id);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($pengajuan) {
                // Cari gudang koperasi
                $gudangKoperasi = \App\Models\Gudang::where('jenis_gudang', 'koperasi')->first();
                if (!$gudangKoperasi) {
                    throw new \Exception("Gudang Koperasi belum dibuat.");
                }

                // Cek stok koperasi
                $stokKoperasi = \App\Models\Stok::where('gudang_id', $gudangKoperasi->id)
                    ->where('jenis_kentang_id', $pengajuan->jenis_kentang_id)
                    ->first();

                if (!$stokKoperasi || $stokKoperasi->stok_dijual < $pengajuan->jumlah_kg) {
                    throw new \Exception("Stok Koperasi tidak mencukupi untuk memenuhi pengajuan ini.");
                }

                // Potong stok koperasi
                $stokKoperasi->stok_dijual = max(0, $stokKoperasi->stok_dijual - $pengajuan->jumlah_kg);
                $stokKoperasi->jumlah_stok = max(0, $stokKoperasi->jumlah_stok - $pengajuan->jumlah_kg);
                $stokKoperasi->save();

                // Tambah ke stok Petani (dapet benih)
                $gudangPetani = \App\Models\Gudang::where('user_id', $pengajuan->petani_id)
                    ->where('jenis_gudang', 'petani')
                    ->first();

                if (!$gudangPetani) {
                    throw new \Exception("GUDANG_KOSONG");
                }

                $stokPetani = \App\Models\Stok::firstOrNew([
                    'gudang_id' => $gudangPetani->id,
                    'jenis_kentang_id' => $pengajuan->jenis_kentang_id,
                    'grade' => 'A'
                ]);

                $stokPetani->jumlah_stok = ($stokPetani->jumlah_stok ?? 0) + $pengajuan->jumlah_kg;
                $stokPetani->save();

                // Catat ke Distribusi Benih (Riwayat)
                \App\Models\DistribusiBenih::create([
                    'koperasi_id' => $pengajuan->koperasi_id,
                    'petani_id' => $pengajuan->petani_id,
                    'jenis_kentang_id' => $pengajuan->jenis_kentang_id,
                    'jumlah_kg' => $pengajuan->jumlah_kg,
                    'total_harga' => $pengajuan->tipe_pengajuan === 'meminta' ? 0 : 0, 
                    'tanggal_transaksi' => now()->toDateString(),
                    'status' => $pengajuan->tipe_pengajuan === 'meminta' ? 'lunas' : 'belum lunas'
                ]);

                // Update status pengajuan
                $pengajuan->update(['status' => 'disetujui']);

                 Notifikasi::create([
                    'user_id' => $pengajuan->petani_id,
                    'pesan' => 'Pengajuan benih sejumlah ' . $pengajuan->jumlah_kg . ' kg telah disetujui.',
                    'tipe_notifikasi' => 'pengajuan_disetujui',
                    'terkait_id' => $pengajuan->id,
                    'url' => route('pengajuan-benih.petani')
                ]);
            });

            return back()->with('success', 'Pengajuan disetujui dan stok berhasil didistribusikan.');
        } catch (\Exception $e) {
            if ($e->getMessage() === 'GUDANG_KOSONG') {
                Notifikasi::create([
                    'user_id' => $pengajuan->petani_id,
                    'pesan' => 'Koperasi menyetujui pengajuan benih Anda, tetapi Anda belum memiliki gudang. Silakan buat gudang terlebih dahulu agar benih dapat disimpan.',
                    'tipe_notifikasi' => 'gudang_kosong',
                    'terkait_id' => $pengajuan->id,
                    'url' => route('petani-gudang.create')
                ]);
                return back()->with('error', 'Gagal menyetujui pengajuan: Petani belum memiliki gudang. Notifikasi telah dikirim ke petani untuk membuat gudang.');
            }
            return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $pengajuan = PengajuanBenih::findOrFail($id);
        $pengajuan->update(['status' => 'ditolak']);

        Notifikasi::create([
            'user_id' => $pengajuan->petani_id,
            'pesan' => 'Pengajuan benih sejumlah ' . $pengajuan->jumlah_kg . ' kg telah ditolak.',
            'tipe_notifikasi' => 'pengajuan_digantung', // atau pengajuan_ditolak
            'terkait_id' => $pengajuan->id,
            'url' => route('pengajuan-benih.petani')
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}
