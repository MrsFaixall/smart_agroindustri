<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_buah_id',
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

    public function penjualanBuah()
    {
        return $this->belongsTo(PenjualanBuah::class, 'penjualan_buah_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }
}
