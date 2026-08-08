<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    AuthController,
    UserController
};
use App\Http\Controllers\Master\{
    BbmController,
    HargaController,
    JenisKentangController,
    KategoriKentangController,
    MetodePembayaranController,
    PengaturanController
};
use App\Http\Controllers\Koperasi\{
    KoperasiGudangStokController,
    KoperasiHargaPasarController,
    KoperasiLaporanController,
    KoperasiQrController,
    KoperasiStokController,
    PenawaranPanenKoperasiController
};
use App\Http\Controllers\Petani\{
    PenanamanBenihController,
    PenawaranPanenPetaniController,
    PetaniLaporanController,
    GudangController,
    LaporanController,
    StokController
};
use App\Http\Controllers\Mitra\{
    MitraGudangController
};
use App\Http\Controllers\Transaksi\{
    DaftarTransaksiController,
    DistribusiBenihController,
    PanenController,
    PembelianController,
    PenjualanBuahController,
    PengadaanBenihController,
    PengajuanBenihController
};
use App\Http\Controllers\Pembayaran\{
    PembayaranController,
    PembayaranDistribusiController,
    PembayaranPenjualanController,
    MidtransController
};
use App\Http\Controllers\Shared\{
    DashboardController,
    NotifikasiController,
    PublicTrackingController
};

// ==========================================
// AUTHENTICATION
// ==========================================
Route::get('/', function () {
    return view('welcome.index');
});
Route::get('/tentang-kami', function () {
    return view('welcome.tentang-kami');
})->name('welcome.tentang-kami');
Route::get('/layanan', function () {
    return view('welcome.layanan');
})->name('welcome.layanan');
Route::get('/kontak', function () {
    return view('welcome.kontak');
})->name('welcome.kontak');
Route::get('/qr-kentang', function () {
    return view('welcome.qr-kentang');
})->name('welcome.qr-kentang');
Route::get('/penjualan-buah/{id}/print-qr', function ($id) {
    $transaksi = \App\Models\PenjualanBuah::with(['koperasi', 'jenisKentang', 'pembeli'])->findOrFail($id);
    return view('koperasi.penjualan-buah.print-qr', compact('transaksi'));
})->name('penjualan-buah.print-qr');
Route::get('/lacak/{token}', [PublicTrackingController::class, 'track'])->name('public.track');
Route::get('/api/lacak/{token}', [PublicTrackingController::class, 'apiTrack'])->name('public.track.api');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// PROTECTED ROUTES (CRUD & DASHBOARD)
// ==========================================
Route::middleware(['auth.custom'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');

    //master data (admin)
    Route::resource('admin/bbm', BbmController::class, ['as' => 'admin']);
    Route::resource('admin/jenis_kentang', JenisKentangController::class, ['as' => 'admin']);
    Route::resource('admin/kategori_kentang', KategoriKentangController::class, ['as' => 'admin']);
    Route::resource('pengguna', UserController::class);

    //petani
    // 2. CRUD Gudang (Gunakan Resource agar singkat)
    Route::middleware('role:petani,admin,super admin')->group(function() {
        Route::get('petani-gudang/wilayah/{level}/{parentId?}', [GudangController::class, 'wilayah'])
            ->whereIn('level', ['provinsi', 'kota', 'kecamatan', 'kelurahan'])
            ->whereNumber('parentId')
            ->name('petani-gudang.wilayah');
        Route::get('petani-gudang/cari-lokasi', [GudangController::class, 'cariLokasi'])->name('petani-gudang.cari-lokasi');
        Route::resource('petani-gudang', GudangController::class);
        
        Route::resource('stok', StokController::class);
            
        // Penawaran Penjualan Panen (ke Koperasi)
        Route::resource('penawaran-panen', PenawaranPanenPetaniController::class, [
            'as' => 'petani'
        ])->only(['index', 'create', 'store', 'update']);
            
        // Pembayaran
        Route::resource('pembayaran-petani', PembayaranController::class);
        Route::post('pembayaran-petani/{id}/accept', [PembayaranController::class, 'accept'])->name('pembayaran-petani.accept');

        // Tambahan Pembayaran Petani (Collapsible)
        Route::get('/petani/pembayaran/penjualan', [PembayaranPenjualanController::class, 'index'])->name('petani.pembayaran.penjualan');
        Route::get('/petani/pembayaran/distribusi', [PembayaranDistribusiController::class, 'index'])->name('petani.pembayaran.distribusi');

        Route::resource('atur-harga', HargaController::class);
    });
    Route::resource('mitra-gudang', MitraGudangController::class);

    // ==========================================
    // MITRA MODULE ROUTES
    // ==========================================
    Route::middleware('role:mitra,admin,super admin')->group(function() {
        // Pembelian Mitra
        Route::get('mitra/pembelian', [\App\Http\Controllers\Mitra\MitraPembelianController::class, 'index'])->name('mitra.pembelian.index');
        Route::post('mitra/pembelian/{id}/bayar', [\App\Http\Controllers\Mitra\MitraPembelianController::class, 'bayar'])->name('mitra.pembelian.bayar');

        // Penjualan Mitra
        Route::resource('mitra/penjualan', \App\Http\Controllers\Mitra\MitraPenjualanController::class, ['as' => 'mitra']);
        Route::post('mitra/penjualan/{id}/bayar', [\App\Http\Controllers\Mitra\MitraPenjualanController::class, 'bayar'])->name('mitra.penjualan.bayar');

        // Stok Mitra
        Route::get('mitra/stok', [\App\Http\Controllers\Mitra\MitraStokController::class, 'index'])->name('mitra.stok.index');

        // Pembayaran Mitra
        Route::get('mitra/pembayaran', [\App\Http\Controllers\Mitra\MitraPembayaranController::class, 'index'])->name('mitra.pembayaran.index');
        Route::get('mitra/pembayaran/create', [\App\Http\Controllers\Mitra\MitraPembayaranController::class, 'create'])->name('mitra.pembayaran.create');
        Route::post('mitra/pembayaran', [\App\Http\Controllers\Mitra\MitraPembayaranController::class, 'store'])->name('mitra.pembayaran.store');
        Route::get('mitra/pembayaran/{id}/invoice', [\App\Http\Controllers\Mitra\MitraPembayaranController::class, 'invoice'])->name('mitra.pembayaran.invoice');
        Route::get('mitra/pembayaran/{id}/struk', [\App\Http\Controllers\Mitra\MitraPembayaranController::class, 'cetakStruk'])->name('mitra.pembayaran.struk');

        // Laporan Mitra
        Route::get('mitra/laporan/pembelian', [\App\Http\Controllers\Mitra\MitraLaporanController::class, 'pembelian'])->name('mitra.laporan.pembelian');
        Route::get('mitra/laporan/pembelian/export', [\App\Http\Controllers\Mitra\MitraLaporanController::class, 'exportPembelian'])->name('mitra.laporan.pembelian.export');
        Route::get('mitra/laporan/penjualan', [\App\Http\Controllers\Mitra\MitraLaporanController::class, 'penjualan'])->name('mitra.laporan.penjualan');
        Route::get('mitra/laporan/penjualan/export', [\App\Http\Controllers\Mitra\MitraLaporanController::class, 'exportPenjualan'])->name('mitra.laporan.penjualan.export');

        // Layanan/Riwayat Mitra
        Route::get('mitra/layanan/riwayat-pembelian', [\App\Http\Controllers\Mitra\MitraLayananController::class, 'riwayatPembelian'])->name('mitra.layanan.riwayat-pembelian');
        Route::get('mitra/layanan/riwayat-penjualan', [\App\Http\Controllers\Mitra\MitraLayananController::class, 'riwayatPenjualan'])->name('mitra.layanan.riwayat-penjualan');
    });

    // 3. CRUD Pembelian
    Route::resource('pembelian', PembelianController::class);

    // 3.1. CRUD Transaksi Koperasi (Pengadaan Benih, Distribusi Benih, Penjualan Buah)
    Route::middleware('role:petani,admin,super admin')->group(function() {
        Route::get('petani/pengajuan-benih', [PengajuanBenihController::class, 'indexPetani'])->name('pengajuan-benih.petani');
        Route::get('petani/pengajuan-benih/create', [PengajuanBenihController::class, 'create'])->name('pengajuan-benih.create');
        Route::post('petani/pengajuan-benih', [PengajuanBenihController::class, 'store'])->name('pengajuan-benih.store');
    });
    
    Route::middleware('role:koperasi,admin,super admin')->group(function() {
        Route::get('koperasi/pengajuan-benih', [PengajuanBenihController::class, 'indexKoperasi'])->name('pengajuan-benih.koperasi');
        Route::post('koperasi/pengajuan-benih/{id}/approve', [PengajuanBenihController::class, 'approve'])->name('pengajuan-benih.approve');
        Route::post('koperasi/pengajuan-benih/{id}/reject', [PengajuanBenihController::class, 'reject'])->name('pengajuan-benih.reject');
    });

    Route::get('pengadaan-benih', [PengadaanBenihController::class, 'index'])->name('pengadaan-benih.index');
    Route::get('pengadaan-benih/create', [PengadaanBenihController::class, 'create'])->name('pengadaan-benih.create');
    Route::post('pengadaan-benih', [PengadaanBenihController::class, 'store'])->name('pengadaan-benih.store');
    Route::post('pengadaan-benih/{id}/bayar', [PengadaanBenihController::class, 'bayar'])->name('pengadaan-benih.bayar');
    Route::delete('pengadaan-benih/{id}', [PengadaanBenihController::class, 'destroy'])->name('pengadaan-benih.destroy');

    Route::get('distribusi-benih', [DistribusiBenihController::class, 'index'])->name('distribusi-benih.index');
    Route::get('distribusi-benih/create', [DistribusiBenihController::class, 'create'])->name('distribusi-benih.create');
    Route::post('distribusi-benih', [DistribusiBenihController::class, 'store'])->name('distribusi-benih.store');
    Route::post('distribusi-benih/{id}/bayar', [DistribusiBenihController::class, 'bayar'])->name('distribusi-benih.bayar');
    Route::post('distribusi-benih/{id}/request-hasil', [DistribusiBenihController::class, 'requestHasil'])->name('distribusi-benih.request-hasil');
    Route::delete('distribusi-benih/{id}', [DistribusiBenihController::class, 'destroy'])->name('distribusi-benih.destroy');

    // Riwayat Layanan (Petani & Koperasi) — tanpa controller baru, pakai closure
    Route::middleware('role:petani,admin,super admin')->group(function() {
        Route::get('petani/layanan/riwayat-pengajuan-benih', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\PengajuanBenih::with(['koperasi', 'jenisKentang']);
            if ($user->role === 'petani') {
                $query->where('petani_id', $user->id);
            }
            $pengajuans = $query->latest()->paginate(10);
            return view('petani.layanan.riwayat_pengajuan_benih.index', compact('pengajuans'));
        })->name('petani.layanan.riwayat-pengajuan-benih');

        Route::get('petani/layanan/riwayat-distribusi-benih', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\DistribusiBenih::with(['petani', 'jenisKentang', 'koperasi']);
            if ($user->role === 'petani') {
                $query->where('petani_id', $user->id);
            }
            $transaksis = $query->latest()->paginate(10)->withQueryString();
            $totalNilai = $query->sum('total_harga');
            return view('petani.layanan.riwayat_distribusi_benih.index', compact('transaksis', 'totalNilai'));
        })->name('petani.layanan.riwayat-distribusi-benih');

        Route::get('petani/layanan/riwayat-pembelian', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\PenjualanBuah::with(['koperasi', 'pembeli', 'jenisKentang']);
            if ($user->role === 'petani') {
                $query->where('pembeli_id', $user->id);
            }
            $transaksis = $query->latest()->paginate(10);
            return view('petani.layanan.riwayat_pembelian.index', compact('transaksis'));
        })->name('petani.layanan.riwayat-pembelian');

        Route::get('petani/layanan/riwayat-penjualan', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\Pembelian::with(['petani', 'koperasi', 'jenisKentang']);
            if ($user->role === 'petani') {
                $query->where('petani_id', $user->id);
            }
            $transaksis = $query->latest()->paginate(10);
            return view('petani.layanan.riwayat_penjualan.index', compact('transaksis'));
        })->name('petani.layanan.riwayat-penjualan');

        Route::get('petani/layanan/riwayat-pembayaran', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\Pembayaran::with(['pembelian.koperasi', 'metodePembayaran'])->whereHas('pembelian', function($q) use ($user) {
                if ($user->role === 'petani') {
                    $q->where('petani_id', $user->id);
                }
            });
            $transaksis = $query->latest()->paginate(10);
            return view('petani.layanan.riwayat_pembayaran.index', compact('transaksis'));
        })->name('petani.layanan.riwayat-pembayaran');
    });

    Route::middleware('role:koperasi,admin,super admin')->group(function() {
        Route::get('koperasi/layanan/riwayat-pengajuan-benih', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\PengajuanBenih::with(['petani', 'jenisKentang']);
            if ($user->role === 'koperasi') {
                $query->where('koperasi_id', $user->id);
            }
            $pengajuans = $query->latest()->paginate(10);
            return view('koperasi.layanan.riwayat_pengajuan_benih.index', compact('pengajuans'));
        })->name('koperasi.layanan.riwayat-pengajuan-benih');

        Route::get('koperasi/layanan/riwayat-distribusi-benih', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\DistribusiBenih::with(['petani', 'jenisKentang', 'koperasi']);
            if ($user->role === 'koperasi') {
                $query->where('koperasi_id', $user->id);
            }
            $transaksis = $query->latest()->paginate(10)->withQueryString();
            $totalNilai = $query->sum('total_harga');
            return view('koperasi.layanan.riwayat_distribusi_benih.index', compact('transaksis', 'totalNilai'));
        })->name('koperasi.layanan.riwayat-distribusi-benih');

        Route::get('koperasi/layanan/riwayat-pembelian', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\Pembelian::with(['petani', 'koperasi', 'jenisKentang']);
            if ($user->role === 'koperasi') {
                $query->where('koperasi_id', $user->id);
            }
            $transaksis = $query->latest()->paginate(10);
            return view('koperasi.layanan.riwayat_pembelian.index', compact('transaksis'));
        })->name('koperasi.layanan.riwayat-pembelian');

        Route::get('koperasi/layanan/riwayat-pembayaran', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\Pembayaran::with(['pembelian.petani', 'metodePembayaran'])->whereHas('pembelian', function($q) use ($user) {
                if ($user->role === 'koperasi') {
                    $q->where('koperasi_id', $user->id);
                }
            });
            $transaksis = $query->latest()->paginate(10);
            return view('koperasi.layanan.riwayat_pembayaran.index', compact('transaksis'));
        })->name('koperasi.layanan.riwayat-pembayaran');
    });

    Route::middleware('role:petani,admin,super admin')->group(function() {
        Route::get('petani/layanan/riwayat-penawaran-panen', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\PenawaranPanen::with(['koperasi', 'jenisKentang', 'gudang']);
            if ($user->role === 'petani') {
                $query->where('petani_id', $user->id);
            }
            $query->whereIn('status', ['disetujui', 'ditolak']);
            $penawarans = $query->latest()->paginate(10);
            return view('petani.layanan.riwayat_penawaran_panen.index', compact('penawarans'));
        })->name('petani.layanan.riwayat-penawaran-panen');
    });

    Route::middleware('role:koperasi,admin,super admin')->group(function() {
        Route::get('koperasi/layanan/riwayat-penawaran-panen', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $query = \App\Models\PenawaranPanen::with(['petani', 'jenisKentang', 'gudang']);
            if ($user->role === 'koperasi') {
                $query->where('koperasi_id', $user->id);
            }
            $query->whereIn('status', ['disetujui', 'ditolak']);
            $penawarans = $query->latest()->paginate(10);
            return view('koperasi.layanan.riwayat_penawaran_panen.index', compact('penawarans'));
        })->name('koperasi.layanan.riwayat-penawaran-panen');
    });

    Route::get('penjualan-buah', [PenjualanBuahController::class, 'index'])->name('penjualan-buah.index');
    Route::get('penjualan-buah/create', [PenjualanBuahController::class, 'create'])->name('penjualan-buah.create');
    Route::post('penjualan-buah', [PenjualanBuahController::class, 'store'])->name('penjualan-buah.store');
    Route::post('penjualan-buah/{id}/bayar', [PenjualanBuahController::class, 'bayar'])->name('penjualan-buah.bayar');
    Route::delete('penjualan-buah/{id}', [PenjualanBuahController::class, 'destroy'])->name('penjualan-buah.destroy');

    // Penawaran Masuk (Dari Petani)
    Route::middleware('role:koperasi,admin,super admin')->group(function() {
        Route::resource('koperasi/penawaran-panen', PenawaranPanenKoperasiController::class, [
            'as' => 'koperasi'
        ])->only(['index', 'update']);
        Route::get('koperasi/qr-code', [KoperasiQrController::class, 'index'])->name('koperasi.qr-code.index');
    });

    // 3.2. Koperasi Gudang & Stok
    Route::middleware('role:koperasi,admin,super admin')->group(function() {
        Route::get('koperasi/gudang-stok', [KoperasiGudangStokController::class, 'index'])->name('koperasi.gudang-stok.index');
        Route::get('koperasi/gudang-stok/create-gudang', [KoperasiGudangStokController::class, 'createGudang'])->name('koperasi.gudang-stok.create-gudang');
        Route::post('koperasi/gudang-stok/store-gudang', [KoperasiGudangStokController::class, 'storeGudang'])->name('koperasi.gudang-stok.store-gudang');
        Route::get('koperasi/gudang-stok/edit-gudang/{id}', [KoperasiGudangStokController::class, 'editGudang'])->name('koperasi.gudang-stok.edit-gudang');
        Route::put('koperasi/gudang-stok/update-gudang/{id}', [KoperasiGudangStokController::class, 'updateGudang'])->name('koperasi.gudang-stok.update-gudang');
        Route::delete('koperasi/gudang-stok/destroy-gudang/{id}', [KoperasiGudangStokController::class, 'destroyGudang'])->name('koperasi.gudang-stok.destroy-gudang');
        Route::get('koperasi/gudang-stok/edit-stok/{id}', [KoperasiGudangStokController::class, 'editStok'])->name('koperasi.gudang-stok.edit-stok');
        Route::put('koperasi/gudang-stok/update-stok/{id}', [KoperasiGudangStokController::class, 'updateStok'])->name('koperasi.gudang-stok.update-stok');
        Route::delete('koperasi/gudang-stok/destroy-stok/{id}', [KoperasiGudangStokController::class, 'destroyStok'])->name('koperasi.gudang-stok.destroy-stok');
    });

    // 3.3. Koperasi Harga Pasar & Laporan
    Route::middleware('role:koperasi,admin,super admin')->group(function() {
        Route::resource('koperasi/atur-harga-pasar', KoperasiHargaPasarController::class, ['as' => 'koperasi']);
        
        // Laporan Koperasi
        Route::get('koperasi/laporan/pengajuan-benih', [KoperasiLaporanController::class, 'pengajuanBenih'])->name('koperasi.laporan.pengajuan-benih');
        Route::get('koperasi/laporan/pengajuan-benih/export', [KoperasiLaporanController::class, 'exportPengajuanBenih'])->name('koperasi.laporan.pengajuan-benih.export');
        Route::get('koperasi/laporan/distribusi-benih', [KoperasiLaporanController::class, 'distribusiBenih'])->name('koperasi.laporan.distribusi-benih');
        Route::get('koperasi/laporan/distribusi-benih/export', [KoperasiLaporanController::class, 'exportDistribusiBenih'])->name('koperasi.laporan.distribusi-benih.export');
        Route::get('koperasi/laporan/penawaran-panen', [KoperasiLaporanController::class, 'penawaranPanen'])->name('koperasi.laporan.penawaran-panen');
        Route::get('koperasi/laporan/penawaran-panen/export', [KoperasiLaporanController::class, 'exportPenawaranPanen'])->name('koperasi.laporan.penawaran-panen.export');
        Route::get('koperasi/laporan/pembelian', [KoperasiLaporanController::class, 'pembelian'])->name('koperasi.laporan.pembelian');
        Route::get('koperasi/laporan/pembelian/export', [KoperasiLaporanController::class, 'exportPembelian'])->name('koperasi.laporan.pembelian.export');
        Route::get('koperasi/laporan/pembayaran', [KoperasiLaporanController::class, 'pembayaran'])->name('koperasi.laporan.pembayaran');
        Route::get('koperasi/laporan/pembayaran/export', [KoperasiLaporanController::class, 'exportPembayaran'])->name('koperasi.laporan.pembayaran.export');
    });

    // 4. CRUD Panen & Laporan Petani
    Route::middleware('role:petani,admin,super admin')->group(function() {
        Route::get('/penanaman', [PenanamanBenihController::class, 'index'])->name('penanaman.index');
        Route::get('/penanaman/create', [PenanamanBenihController::class, 'create'])->name('penanaman.create');
        Route::post('/penanaman', [PenanamanBenihController::class, 'store'])->name('penanaman.store');
        Route::delete('/penanaman/{id}', [PenanamanBenihController::class, 'destroy'])->name('penanaman.destroy');

        Route::resource('panen', PanenController::class);
        
        // Laporan Petani
        Route::get('petani/laporan/pengajuan-benih', [PetaniLaporanController::class, 'pengajuanBenih'])->name('petani.laporan.pengajuan-benih');
        Route::get('petani/laporan/pengajuan-benih/export', [PetaniLaporanController::class, 'exportPengajuanBenih'])->name('petani.laporan.pengajuan-benih.export');
        Route::get('petani/laporan/distribusi-benih', [PetaniLaporanController::class, 'distribusiBenih'])->name('petani.laporan.distribusi-benih');
        Route::get('petani/laporan/distribusi-benih/export', [PetaniLaporanController::class, 'exportDistribusiBenih'])->name('petani.laporan.distribusi-benih.export');
        Route::get('petani/laporan/penawaran-panen', [PetaniLaporanController::class, 'penawaranPanen'])->name('petani.laporan.penawaran-panen');
        Route::get('petani/laporan/penawaran-panen/export', [PetaniLaporanController::class, 'exportPenawaranPanen'])->name('petani.laporan.penawaran-panen.export');
        Route::get('petani/laporan/pembelian', [PetaniLaporanController::class, 'pembelian'])->name('petani.laporan.pembelian');
        Route::get('petani/laporan/pembelian/export', [PetaniLaporanController::class, 'exportPembelian'])->name('petani.laporan.pembelian.export');
        Route::get('petani/laporan/penjualan', [PetaniLaporanController::class, 'penjualan'])->name('petani.laporan.penjualan');
        Route::get('petani/laporan/penjualan/export', [PetaniLaporanController::class, 'exportPenjualan'])->name('petani.laporan.penjualan.export');
        Route::get('petani/laporan/pembayaran', [PetaniLaporanController::class, 'pembayaran'])->name('petani.laporan.pembayaran');
        Route::get('petani/laporan/pembayaran/export', [PetaniLaporanController::class, 'exportPembayaran'])->name('petani.laporan.pembayaran.export');
    });

    // 5. CRUD Stok
    Route::resource('stok', StokController::class);

    // 6. CRUD Metode Pembayaran (Petani menyimpan nomor bank)
    Route::resource('metode-pembayaran', MetodePembayaranController::class);

    // 7. CRUD Transaksi Pembayaran (Koperasi lakukan pembayaran)
    Route::resource('pembayaran', PembayaranController::class)->only(['index', 'create', 'store']);
    Route::post('/koperasi/pembayaran/notify-petani', [PembayaranController::class, 'notifyPetani'])->name('koperasi.pembayaran.notify-petani');

    // Tambahan Pembayaran Koperasi (Collapsible)
    Route::get('/koperasi/pembayaran/penjualan', [PembayaranPenjualanController::class, 'index'])->name('koperasi.pembayaran.penjualan');
    Route::get('/koperasi/pembayaran/penjualan/create', [PembayaranPenjualanController::class, 'create'])->name('koperasi.pembayaran.penjualan.create');
    Route::post('/koperasi/pembayaran/penjualan', [PembayaranPenjualanController::class, 'store'])->name('koperasi.pembayaran.penjualan.store');
    Route::get('/koperasi/pembayaran/penjualan/{id}/invoice', [PembayaranPenjualanController::class, 'invoice'])->name('koperasi.pembayaran.penjualan.invoice');
    Route::get('/koperasi/pembayaran/penjualan/{id}/struk', [PembayaranPenjualanController::class, 'cetakStruk'])->name('koperasi.pembayaran.penjualan.struk');

    Route::get('/koperasi/pembayaran/distribusi', [PembayaranDistribusiController::class, 'index'])->name('koperasi.pembayaran.distribusi');
    Route::get('/koperasi/pembayaran/distribusi/create', [PembayaranDistribusiController::class, 'create'])->name('koperasi.pembayaran.distribusi.create');
    Route::post('/koperasi/pembayaran/distribusi', [PembayaranDistribusiController::class, 'store'])->name('koperasi.pembayaran.distribusi.store');
    Route::get('/koperasi/pembayaran/distribusi/{id}/invoice', [PembayaranDistribusiController::class, 'invoice'])->name('koperasi.pembayaran.distribusi.invoice');
    Route::get('/koperasi/pembayaran/distribusi/{id}/struk', [PembayaranDistribusiController::class, 'cetakStruk'])->name('koperasi.pembayaran.distribusi.struk');

    Route::get('/pembayaran/{id}/invoice', [PembayaranController::class, 'invoice'])->name('pembayaran.invoice');
    Route::get('/pembayaran/{id}/struk', [PembayaranController::class, 'cetakStruk'])->name('pembayaran.struk');

    // 7.1 Daftar Transaksi (Halaman Khusus Riwayat Transaksi & Struk/Invoice)
    Route::get('/daftar-transaksi', [DaftarTransaksiController::class, 'index'])->name('daftar-transaksi.index');

    // 8. Laporan (Hanya view, tidak perlu resource penuh)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // 8. Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan/update', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Midtrans Snap Token & Finish Route (Butuh Auth)
    Route::post('/midtrans/snap-token', [MidtransController::class, 'createSnapToken'])->name('midtrans.snap-token');
    Route::get('/pembayaran/finish', [MidtransController::class, 'finish'])->name('pembayaran.finish');
});

// Midtrans Webhook (Tanpa Auth)
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

Route::middleware(['auth'])->group(function () {
    Route::resource('koperasi/stok-koperasi', KoperasiStokController::class, [
        'as' => 'koperasi'
    ])->except(['show']);
});
