<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenawaranPanen extends Model
{
    protected $guarded = [];

    public function petani()
    {
        return $this->belongsTo(User::class, 'petani_id');
    }

    public function koperasi()
    {
        return $this->belongsTo(User::class, 'koperasi_id');
    }

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
}
