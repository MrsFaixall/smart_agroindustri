<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $guarded = [];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function getKodeInvAttribute()
    {
        $date = \Carbon\Carbon::parse($this->tanggal_pembayaran ?? $this->created_at);
        $dateStr = $date->format('dmy');

        $sequence = static::whereDate('tanggal_pembayaran', $date->toDateString())
            ->where('id', '<=', $this->id)
            ->count();

        if ($sequence === 0) {
            $sequence = 1;
        }

        return 'INV-' . $dateStr . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
