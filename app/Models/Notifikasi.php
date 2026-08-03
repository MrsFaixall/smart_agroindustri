<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getSystemAlerts()
    {
        $alerts = collect();
        if (!auth()->check()) return $alerts;

        $user = auth()->user();

        // 1. Cek Kelengkapan Profil (Berlaku untuk semua role)
        if (empty($user->no_telp) || empty($user->alamat)) {
            $alerts->push((object)[
                'id' => 'sys_profil',
                'tipe_notifikasi' => 'system_alert',
                'pesan' => 'Profil Anda belum lengkap! Silakan lengkapi Alamat dan Nomor Telepon Anda pada menu Pengaturan agar mempermudah komunikasi dan pengiriman.',
                'url' => route('pengaturan.index'),
                'is_read' => false,
                'created_at' => now(),
                'is_system' => true
            ]);
        }

        // 2. Cek Metode Pembayaran (Berlaku untuk semua role)
        if (!\App\Models\MetodePembayaran::where('user_id', $user->id)->exists()) {
            $alerts->push((object)[
                'id' => 'sys_metode_pembayaran',
                'tipe_notifikasi' => 'system_alert',
                'pesan' => 'Anda belum menambahkan Metode Pembayaran/Rekening Bank! Silakan masuk ke menu Pengaturan > Metode Pembayaran, lalu tambahkan nomor rekening Anda agar dapat melakukan atau menerima pembayaran.',
                'url' => route('metode-pembayaran.index'),
                'is_read' => false,
                'created_at' => now(),
                'is_system' => true
            ]);
        }

        if ($user->role === 'koperasi') {
            if (!\App\Models\Gudang::where('jenis_gudang', 'koperasi')->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_gudang_koperasi',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum memiliki Gudang Koperasi! Silakan masuk ke menu Manajemen Koperasi > Gudang Koperasi, lalu klik "Tambah Gudang" agar Anda dapat menerima dan menyimpan komoditas.',
                    'url' => route('koperasi.gudang-stok.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
            if (!\App\Models\HargaPasar::exists()) {
                $alerts->push((object)[
                    'id' => 'sys_harga_pasar',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Harga Pasar Acuan belum diatur! Masuk ke menu Manajemen Koperasi > Atur Harga Pasar, lalu perbarui daftar harga acuan agar petani memiliki panduan harga jual yang adil.',
                    'url' => route('koperasi.atur-harga-pasar.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
            if (!\App\Models\PengajuanBenih::exists()) {
                $alerts->push((object)[ 'id' => 'sys_kop_pengajuan', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Belum ada pengajuan benih dari petani. Pantau menu ini untuk memproses permintaan bibit.', 'url' => route('pengajuan-benih.koperasi'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\DistribusiBenih::exists()) {
                $alerts->push((object)[ 'id' => 'sys_kop_distribusi', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Belum ada distribusi benih ke petani. Mulai distribusikan benih yang tersedia.', 'url' => route('distribusi-benih.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\Pembelian::exists()) {
                $alerts->push((object)[ 'id' => 'sys_kop_pembelian', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Belum ada transaksi pembelian panen dari petani.', 'url' => route('pembelian.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\PenjualanBuah::exists()) {
                $alerts->push((object)[ 'id' => 'sys_kop_penjualan', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Belum ada transaksi penjualan buah ke pembeli.', 'url' => route('penjualan-buah.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\PenawaranPanen::exists()) {
                $alerts->push((object)[ 'id' => 'sys_kop_penawaran', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Belum ada penawaran masuk dari petani.', 'url' => route('koperasi.penawaran-panen.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\Stok::whereHas('gudang', function($q) { $q->where('jenis_gudang', 'koperasi'); })->exists()) {
                $alerts->push((object)[ 'id' => 'sys_kop_stok', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Stok Koperasi masih kosong! Lakukan pembelian dari petani untuk menambah stok.', 'url' => route('koperasi.stok-koperasi.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }

        } elseif ($user->role === 'petani') {
            if (!\App\Models\Gudang::where('user_id', $user->id)->where('jenis_gudang', 'petani')->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_gudang_petani',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum memiliki Gudang! Masuk ke menu Pengaturan > Gudang Petani dan tambahkan lokasi penyimpanan Anda untuk menampung benih dan hasil panen.',
                    'url' => route('petani-gudang.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
            if (!\App\Models\Harga::where('user_id', $user->id)->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_harga_petani',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum mengatur Harga Jual Standar! Masuk ke menu Pengaturan > Atur Harga Petani untuk menentukan harga jual minimal komoditas Anda ke Koperasi.',
                    'url' => route('atur-harga.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
            if (!\App\Models\Panen::whereHas('gudang', function($q) use ($user) { $q->where('user_id', $user->id); })->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_panen_petani',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum mencatat Hasil Panen apa pun! Masuk ke menu Kelola Panen Petani > Hasil Panen dan klik "Tambah Panen Baru" agar Anda dapat menjual panen Anda ke Koperasi.',
                    'url' => route('panen.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
            if (!\App\Models\Stok::whereHas('gudang', function($q) use ($user) { $q->where('user_id', $user->id); })->exists()) {
                $alerts->push((object)[ 'id' => 'sys_stok_petani', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Anda belum memiliki Stok Siap Jual! Pastikan Anda sudah mencatat Hasil Panen yang masuk ke gudang.', 'url' => route('stok.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\PenawaranPanen::where('petani_id', $user->id)->exists()) {
                $alerts->push((object)[ 'id' => 'sys_penawaran_petani', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Anda belum membuat Penawaran Penjualan! Ajukan penawaran ke Koperasi agar hasil panen Anda terjual.', 'url' => route('petani.penawaran-panen.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\PengajuanBenih::where('petani_id', $user->id)->exists()) {
                $alerts->push((object)[ 'id' => 'sys_pengajuan_benih_petani', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Anda belum pernah mengajukan benih! Jika butuh bibit, silakan ajukan di menu Pengajuan Benih.', 'url' => route('pengajuan-benih.petani'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\PenanamanBenih::where('petani_id', $user->id)->exists()) {
                $alerts->push((object)[ 'id' => 'sys_penanaman_petani', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Anda belum mencatat aktivitas penanaman! Catat penanaman benih Anda agar mudah dimonitor.', 'url' => route('penanaman.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }
            if (!\App\Models\DistribusiBenih::where('petani_id', $user->id)->exists()) {
                $alerts->push((object)[ 'id' => 'sys_distribusi_benih_petani', 'tipe_notifikasi' => 'system_alert', 'pesan' => 'Anda belum memiliki riwayat penerimaan benih. Pantau menu ini setelah mengajukan benih.', 'url' => route('distribusi-benih.index'), 'is_read' => false, 'created_at' => now(), 'is_system' => true ]);
            }

        } elseif ($user->role === 'mitra') {
            if (!\App\Models\Gudang::where('user_id', $user->id)->where('jenis_gudang', 'mitra')->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_gudang_mitra',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum memiliki Gudang Mitra! Masuk ke menu Manajemen Mitra > Gudang Mitra dan klik "Tambah Gudang" sebagai lokasi penerimaan pasokan.',
                    'url' => route('mitra-gudang.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
        }

        return $alerts;
    }
}
