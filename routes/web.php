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
    DaftarTransaksiController
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

    //master data (admin)
    Route::resource('admin/bbm', BbmController::class, ['as' => 'admin']);
    Route::resource('admin/jenis_kentang', JenisKentangController::class, ['as' => 'admin']);
    Route::resource('pengguna', UserController::class);

    //petani
    // 2. CRUD Gudang (Gunakan Resource agar singkat)
    Route::get('gudang/wilayah/{level}/{parentId?}', [GudangController::class, 'wilayah'])
        ->whereIn('level', ['provinsi', 'kota', 'kecamatan', 'kelurahan'])
        ->whereNumber('parentId')
        ->name('gudang.wilayah');
    Route::get('gudang/cari-lokasi', [GudangController::class, 'cariLokasi'])->name('gudang.cari-lokasi');
    Route::resource('gudang', GudangController::class);
    Route::resource('atur-harga', HargaController::class);

    // 3. CRUD Pembelian
    Route::resource('pembelian', PembelianController::class);

    // 4. CRUD Panen
    Route::resource('panen', PanenController::class);

    // 5. CRUD Stok
    Route::resource('stok', StokController::class);

    // 6. CRUD Metode Pembayaran (Petani menyimpan nomor bank)
    Route::resource('metode-pembayaran', MetodePembayaranController::class);

    // 7. CRUD Transaksi Pembayaran (Pengepul lakukan pembayaran)
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
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
