<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaPasar extends Model
{
    protected $guarded = [];
    protected $table = 'harga_pasars';

    public function jenisKentang()
    {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }
}
