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
}
