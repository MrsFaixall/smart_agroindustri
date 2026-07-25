<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengadaanBenih extends Model
{
    protected $guarded = [];

    public function koperasi()
    {
        return $this->belongsTo(User::class, 'koperasi_id');
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }
}
