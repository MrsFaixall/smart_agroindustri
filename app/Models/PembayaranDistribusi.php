<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDistribusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'distribusi_benih_id',
        'metode_pembayaran_id',
        'jumlah_bayar',
        'tanggal_pembayaran',
        'status',
        'catatan',
        'midtrans_transaction_id',
        'midtrans_order_id',
        'payment_type',
        'pdf_url'
    ];

    public function distribusiBenih()
    {
        return $this->belongsTo(DistribusiBenih::class, 'distribusi_benih_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }
}
