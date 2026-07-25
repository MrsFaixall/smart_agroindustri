<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiBenih extends Model
{
    protected $guarded = [];

    public function koperasi()
    {
        return $this->belongsTo(User::class, 'koperasi_id');
    }

    public function petani()
    {
        return $this->belongsTo(User::class, 'petani_id');
    }

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }
}
