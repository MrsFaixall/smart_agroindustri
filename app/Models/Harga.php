<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    protected $guarded = [];

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
