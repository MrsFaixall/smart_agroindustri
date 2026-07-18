<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $guarded = [];

    public function stoks()
    {
        return $this->hasMany(Stok::class, 'gudang_id');
    }

    public function panens()
    {
        return $this->hasMany(Panen::class, 'gudang_id');
    }

    public function getKapasitasTerpakaiAttribute()
    {
        return $this->stoks()->sum('jumlah_stok');
    }

    public function getPersentaseKapasitasAttribute()
    {
        if ($this->kapasitas_max <= 0) return 0;
        $terpakai = $this->kapasitas_terpakai;
        $percent = ($terpakai / $this->kapasitas_max) * 100;
        return round($percent, 2);
    }
}
