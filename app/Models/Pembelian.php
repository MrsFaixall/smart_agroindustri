<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pembelian extends Model {
    protected $guarded = [];

    public function petani() {
        return $this->belongsTo(User::class, 'petani_id');
    }

    public function pengepul() {
        return $this->belongsTo(User::class, 'pengepul_id');
    }

    public function jenisKentang() {
        return $this->belongsTo(JenisKentang::class, 'jenis_kentang_id');
    }

    public function pembayarans() {
        return $this->hasMany(Pembayaran::class);
    }
}
