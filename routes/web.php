<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    GudangController,
    PembelianController,
    PanenController,
    StokController,
    PembayaranController,
    MetodePembayaranController,
    LaporanController,
    PengaturanController,
    BbmController,
    JenisKentangController,
    HargaController,
    UserController,
    MidtransController,
    DaftarTransaksiController,
    KoperasiGudangStokController,
    KoperasiHargaPasarController,
    PengadaanBenihController,
    DistribusiBenihController,
    PenjualanBuahController,
    PengajuanBenihController,
    NotifikasiController,
    MitraGudangController
};

// ==========================================
// AUTHENTICATION
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

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
    Route::resource('pengguna', UserController::class);

    //petani
    // 2. CRUD Gudang (Gunakan Resource agar singkat)
    Route::get('petani-gudang/wilayah/{level}/{parentId?}', [GudangController::class, 'wilayah'])
        ->whereIn('level', ['provinsi', 'kota', 'kecamatan', 'kelurahan'])
        ->whereNumber('parentId')
        ->name('petani-gudang.wilayah');
    Route::get('petani-gudang/cari-lokasi', [GudangController::class, 'cariLokasi'])->name('petani-gudang.cari-lokasi');
    Route::resource('petani-gudang', GudangController::class);
    Route::resource('mitra-gudang', MitraGudangController::class);
    Route::resource('stok', StokController::class);
        
    // Penawaran Penjualan Panen (ke Koperasi)
    Route::resource('penawaran-panen', \App\Http\Controllers\PenawaranPanenPetaniController::class, [
        'as' => 'petani'
    ])->only(['index', 'create', 'store', 'update']);
        
    // Pembayaran
    Route::resource('pembayaran-petani', PembayaranController::class);
    Route::resource('atur-harga', HargaController::class);

    // 3. CRUD Pembelian
    Route::resource('pembelian', PembelianController::class);

    // 3.1. CRUD Transaksi Koperasi (Pengadaan Benih, Distribusi Benih, Penjualan Buah)
    Route::get('petani/pengajuan-benih', [PengajuanBenihController::class, 'indexPetani'])->name('pengajuan-benih.petani');
    Route::get('petani/pengajuan-benih/create', [PengajuanBenihController::class, 'create'])->name('pengajuan-benih.create');
    Route::post('petani/pengajuan-benih', [PengajuanBenihController::class, 'store'])->name('pengajuan-benih.store');
    
    Route::get('koperasi/pengajuan-benih', [PengajuanBenihController::class, 'indexKoperasi'])->name('pengajuan-benih.koperasi');
    Route::post('koperasi/pengajuan-benih/{id}/approve', [PengajuanBenihController::class, 'approve'])->name('pengajuan-benih.approve');
    Route::post('koperasi/pengajuan-benih/{id}/reject', [PengajuanBenihController::class, 'reject'])->name('pengajuan-benih.reject');

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

    Route::get('penjualan-buah', [PenjualanBuahController::class, 'index'])->name('penjualan-buah.index');
    Route::get('penjualan-buah/create', [PenjualanBuahController::class, 'create'])->name('penjualan-buah.create');
    Route::post('penjualan-buah', [PenjualanBuahController::class, 'store'])->name('penjualan-buah.store');
    Route::post('penjualan-buah/{id}/bayar', [PenjualanBuahController::class, 'bayar'])->name('penjualan-buah.bayar');
    Route::delete('penjualan-buah/{id}', [PenjualanBuahController::class, 'destroy'])->name('penjualan-buah.destroy');

    // Penawaran Masuk (Dari Petani)
    Route::resource('koperasi/penawaran-panen', \App\Http\Controllers\PenawaranPanenKoperasiController::class, [
        'as' => 'koperasi'
    ])->only(['index', 'update']);

    // 3.2. Koperasi Gudang & Stok
    Route::get('koperasi/gudang-stok', [KoperasiGudangStokController::class, 'index'])->name('koperasi.gudang-stok.index');
    Route::get('koperasi/gudang-stok/create-gudang', [KoperasiGudangStokController::class, 'createGudang'])->name('koperasi.gudang-stok.create-gudang');
    Route::post('koperasi/gudang-stok/store-gudang', [KoperasiGudangStokController::class, 'storeGudang'])->name('koperasi.gudang-stok.store-gudang');
    Route::get('koperasi/gudang-stok/edit-gudang/{id}', [KoperasiGudangStokController::class, 'editGudang'])->name('koperasi.gudang-stok.edit-gudang');
    Route::put('koperasi/gudang-stok/update-gudang/{id}', [KoperasiGudangStokController::class, 'updateGudang'])->name('koperasi.gudang-stok.update-gudang');
    Route::delete('koperasi/gudang-stok/destroy-gudang/{id}', [KoperasiGudangStokController::class, 'destroyGudang'])->name('koperasi.gudang-stok.destroy-gudang');
    Route::get('koperasi/gudang-stok/edit-stok/{id}', [KoperasiGudangStokController::class, 'editStok'])->name('koperasi.gudang-stok.edit-stok');
    Route::put('koperasi/gudang-stok/update-stok/{id}', [KoperasiGudangStokController::class, 'updateStok'])->name('koperasi.gudang-stok.update-stok');
    Route::delete('koperasi/gudang-stok/destroy-stok/{id}', [KoperasiGudangStokController::class, 'destroyStok'])->name('koperasi.gudang-stok.destroy-stok');

    // 3.3. Koperasi Harga Pasar
    Route::resource('koperasi/atur-harga-pasar', KoperasiHargaPasarController::class, ['as' => 'koperasi']);

    // 4. CRUD Panen
        Route::get('/penanaman', [App\Http\Controllers\PenanamanBenihController::class, 'index'])->name('penanaman.index');
        Route::get('/penanaman/create', [App\Http\Controllers\PenanamanBenihController::class, 'create'])->name('penanaman.create');
        Route::post('/penanaman', [App\Http\Controllers\PenanamanBenihController::class, 'store'])->name('penanaman.store');
        Route::delete('/penanaman/{id}', [App\Http\Controllers\PenanamanBenihController::class, 'destroy'])->name('penanaman.destroy');

        Route::resource('panen', App\Http\Controllers\PanenController::class);

    // 5. CRUD Stok
    Route::resource('stok', StokController::class);

    // 6. CRUD Metode Pembayaran (Petani menyimpan nomor bank)
    Route::resource('metode-pembayaran', MetodePembayaranController::class);

    // 7. CRUD Transaksi Pembayaran (Koperasi lakukan pembayaran)
    Route::resource('pembayaran', PembayaranController::class)->only(['index', 'create', 'store']);
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

Route::middleware(['auth'])->group(function () {
    Route::resource('koperasi/stok-koperasi', \App\Http\Controllers\KoperasiStokController::class, [
        'as' => 'koperasi'
    ])->except(['show']);
});
