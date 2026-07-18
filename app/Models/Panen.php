<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_panen' => 'date',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function stok()
    {
        return $this->hasOne(Stok::class, 'panen_id');
    }
}
