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
}
