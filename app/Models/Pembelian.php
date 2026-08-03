<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pembelian extends Model {
    protected $guarded = [];

    public function petani() {
        return $this->belongsTo(User::class, 'petani_id');
    }

    public function koperasi() {
        return $this->belongsTo(User::class, 'koperasi_id');
    }

    public function jenisKentang() {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function pembayarans() {
        return $this->hasMany(Pembayaran::class);
    }

    public function getKodeTrxAttribute() {
        $date = \Carbon\Carbon::parse($this->tanggal_pembelian ?? $this->created_at);
        $dateStr = $date->format('dmy');

        $sequence = static::whereDate('tanggal_pembelian', $date->toDateString())
            ->where('id', '<=', $this->id)
            ->count();

        if ($sequence === 0) {
            $sequence = 1;
        }

        return 'TRX-' . $dateStr . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public static function transferStock($pembelian) {
        $jumlah_dibeli = $pembelian->jumlah_kg;
        $stoks = \App\Models\Stok::where('jenis_kentang_id', $pembelian->jenis_kentang_id)
            ->whereHas('gudang', function($q) use ($pembelian) { 
                $q->where('user_id', $pembelian->petani_id); 
            })
            ->where('jumlah_stok', '>', 0)
            ->orderBy('id')
            ->get();

        $gradeTerakhir = 'A';
        foreach ($stoks as $stok) {
            if ($jumlah_dibeli <= 0) break;
            
            $kurangi = min($jumlah_dibeli, $stok->jumlah_stok);
            $stok->jumlah_stok = max(0, $stok->jumlah_stok - $kurangi);
            if ($stok->stok_dijual > $stok->jumlah_stok) {
                $stok->stok_dijual = $stok->jumlah_stok;
            }
            $stok->save();
            
            $gradeTerakhir = $stok->grade ?? 'A';
            $jumlah_dibeli -= $kurangi;
        }

        $gudangKoperasi = \App\Models\Gudang::firstOrCreate(
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

        $stokKoperasi = \App\Models\Stok::firstOrCreate(
            [
                'gudang_id' => $gudangKoperasi->id,
                'jenis_kentang_id' => $pembelian->jenis_kentang_id,
                'grade' => $gradeTerakhir
            ],
            [
                'jumlah_stok' => 0,
                'stok_dijual' => 0,
            ]
        );

        $stokKoperasi->jumlah_stok += $pembelian->jumlah_kg;
        $stokKoperasi->stok_dijual += $pembelian->jumlah_kg;
        $stokKoperasi->save();

        \App\Models\Notifikasi::create([
            'user_id' => $pembelian->petani_id,
            'pesan' => 'Pembayaran tagihan Anda untuk transaksi ' . $pembelian->kode_trx . ' telah lunas. Stok hasil panen (' . ($pembelian->jenisKentang->nama_jenis ?? '-') . ') sejumlah ' . $pembelian->jumlah_kg . ' kg telah dipindahkan ke Koperasi.',
            'tipe_notifikasi' => 'pembayaran_diterima',
            'terkait_id' => $pembelian->id,
            'url' => route('petani.layanan.riwayat-penjualan')
        ]);
    }

    public static function reverseStockTransfer($pembelian) {
        $gudangKoperasi = \App\Models\Gudang::where('jenis_gudang', 'koperasi')->first();
        $gradeTerakhir = 'A';
        if ($gudangKoperasi) {
            $stokKoperasi = \App\Models\Stok::where('gudang_id', $gudangKoperasi->id)
                ->where('jenis_kentang_id', $pembelian->jenis_kentang_id)
                ->first();
            if ($stokKoperasi) {
                $gradeTerakhir = $stokKoperasi->grade ?? 'A';
                $stokKoperasi->jumlah_stok = max(0, $stokKoperasi->jumlah_stok - $pembelian->jumlah_kg);
                $stokKoperasi->stok_dijual = max(0, $stokKoperasi->stok_dijual - $pembelian->jumlah_kg);
                $stokKoperasi->save();
            }
        }

        $gudangPetani = \App\Models\Gudang::where('user_id', $pembelian->petani_id)->where('jenis_gudang', 'petani')->first();
        if ($gudangPetani) {
            $stokPetani = \App\Models\Stok::firstOrCreate(
                [
                    'gudang_id' => $gudangPetani->id,
                    'jenis_kentang_id' => $pembelian->jenis_kentang_id,
                    'grade' => $gradeTerakhir
                ],
                [
                    'jumlah_stok' => 0,
                    'stok_dijual' => 0,
                ]
            );
            $stokPetani->jumlah_stok += $pembelian->jumlah_kg;
            $stokPetani->stok_dijual += $pembelian->jumlah_kg;
            $stokPetani->save();
        }
    }
}
