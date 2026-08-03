<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKentang extends Model
{
    protected $guarded = [];

    public function jenisKentangs()
    {
        return $this->hasMany(JenisKentang::class, 'kategori_kentang_id');
    }
}
