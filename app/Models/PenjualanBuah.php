<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanBuah extends Model
{
    protected $guarded = [];
    protected $table = 'penjualan_buahs';

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
}
