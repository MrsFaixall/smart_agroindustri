<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKentang extends Model
{
    protected $guarded = [];

    public function hargas()
    {
        return $this->hasMany(Harga::class, 'jenis_kentang_id');
    }

    public function harga()
    {
        return $this->hasOne(Harga::class, 'jenis_kentang_id');
    }

    public function hargaPasar()
    {
        return $this->hasOne(HargaPasar::class, 'jenis_kentang_id');
    }

    public function hargaPasars()
    {
        return $this->hasMany(HargaPasar::class, 'jenis_kentang_id');
    }

    public function stoks()
    {
        return $this->hasMany(Stok::class, 'jenis_kentang_id');
    }

    public function panens()
    {
        return $this->hasMany(Panen::class, 'jenis_kentang_id');
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'jenis_kentang_id');
    }
}
