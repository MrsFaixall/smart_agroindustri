<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenanamanBenih extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'estimasi_panen' => 'date',
    ];

    public function petani()
    {
        return $this->belongsTo(User::class, 'petani_id');
    }

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
        return $this->hasOne(Panen::class, 'penanaman_id');
    }
}