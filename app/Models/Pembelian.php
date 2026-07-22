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

    public function getKodeTrxAttribute() {
        $date = \Carbon\Carbon::parse($this->tanggal_pembelian ?? $this->created_at);
        $dateStr = $date->format('dmy');

        $sequence = static::whereDate('tanggal_pembelian', $date->toDateString())
            ->where('id', '<=', $this->id)
            ->count();

        if ($sequence === 0) {
            $sequence = 1;
        }

        return 'TRX-' . $dateStr . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
