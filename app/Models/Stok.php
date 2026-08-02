<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $guarded = [];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function panen()
    {
        return $this->belongsTo(Panen::class, 'panen_id');
    }

    public function getStokTersimpanAttribute()
    {
        return max(0, $this->jumlah_stok - ($this->stok_dijual ?? 0));
    }

    protected static function booted()
    {
        static::saved(function ($stok) {
            // Batas minimum stok
            $batas_minimum = 100;

            if ($stok->jumlah_stok <= $batas_minimum) {
                $pesan = "Stok benih/panen di gudang Anda sudah menipis (Sisa: {$stok->jumlah_stok} kg). Harap lakukan pengadaan/pengajuan.";
                
                // Cari user yang punya gudang ini
                $gudang = $stok->gudang;
                $user_id = null;
                
                if ($gudang && $gudang->user_id) {
                    $user_id = $gudang->user_id;
                } elseif ($gudang && $gudang->jenis_gudang == 'koperasi') {
                    // Beri notif ke koperasi pertama
                    $koperasi = \App\Models\User::where('role', 'koperasi')->first();
                    if ($koperasi) $user_id = $koperasi->id;
                }

                if ($user_id) {
                    // Hindari spam notifikasi yang sama
                    $exists = Notifikasi::where('user_id', $user_id)
                        ->where('tipe_notifikasi', 'stok_menipis')
                        ->where('terkait_id', $stok->id)
                        ->where('is_read', false)
                        ->exists();

                    if (!$exists) {
                        Notifikasi::create([
                            'user_id' => $user_id,
                            'pesan' => $pesan,
                            'tipe_notifikasi' => 'stok_menipis',
                            'terkait_id' => $stok->id,
                            'url' => url('/stok')
                        ]);
                    }
                }
            }
        });
    }
}
