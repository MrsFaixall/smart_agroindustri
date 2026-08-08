<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanBuah extends Model
{
    protected $guarded = [];
    protected $table = 'penjualan_buahs';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->tracking_token)) {
                $model->tracking_token = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($model->estimasi_waktu)) {
                $model->estimasi_waktu = '6 Jam 15 Menit';
            }
            if (empty($model->routing_path)) {
                // Mock SVG routing coordinate path
                $model->routing_path = 'M60 80 Q 120 100 160 60 T 240 40';
            }
        });
    }

    public function koperasi()
    {
        return $this->belongsTo(User::class, 'koperasi_id');
    }

    public function pembeli()
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function pembayaranPenjualans()
    {
        return $this->hasMany(PembayaranPenjualan::class, 'penjualan_buah_id');
    }

    public static function transferStockToMitra($penjualan)
    {
        // Pastikan pembeli adalah Mitra
        $pembeli = User::find($penjualan->pembeli_id);
        if ($pembeli && $pembeli->role === 'mitra') {
            $gudangMitra = Gudang::where('user_id', $penjualan->pembeli_id)->first();
            if ($gudangMitra) {
                $stokMitra = Stok::firstOrCreate(
                    [
                        'gudang_id' => $gudangMitra->id,
                        'jenis_kentang_id' => $penjualan->jenis_kentang_id,
                        'grade' => $penjualan->grade ?? 'A'
                    ],
                    [
                        'jumlah_stok' => 0,
                        'stok_dijual' => 0,
                    ]
                );

                $stokMitra->jumlah_stok += $penjualan->jumlah_kg;
                $stokMitra->stok_dijual += $penjualan->jumlah_kg;
                $stokMitra->save();
                
                // Tambahkan notifikasi untuk Mitra
                Notifikasi::create([
                    'user_id' => $penjualan->pembeli_id,
                    'pesan' => 'Pembayaran Anda untuk transaksi pembelian hasil panen senilai Rp ' . number_format($penjualan->total_harga, 0, ',', '.') . ' telah lunas. Stok sebanyak ' . $penjualan->jumlah_kg . ' kg telah masuk ke gudang Anda.',
                    'tipe_notifikasi' => 'pembayaran_berhasil',
                    'terkait_id' => $penjualan->id,
                    'url' => route('mitra.stok.index')
                ]);
            }
        }
    }
}
