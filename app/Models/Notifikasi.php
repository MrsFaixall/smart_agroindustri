<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getSystemAlerts()
    {
        $alerts = collect();
        if (!auth()->check()) return $alerts;

        $user = auth()->user();

        if ($user->role === 'koperasi') {
            if (!\App\Models\Gudang::where('jenis_gudang', 'koperasi')->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_gudang_koperasi',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum memiliki Gudang Koperasi. Silakan buat gudang terlebih dahulu agar dapat menerima dan menyimpan benih/panen.',
                    'url' => route('koperasi.gudang-stok.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
        } elseif ($user->role === 'petani') {
            if (!\App\Models\Gudang::where('user_id', $user->id)->where('jenis_gudang', 'petani')->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_gudang_petani',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum memiliki Gudang. Silakan buat gudang terlebih dahulu agar Anda dapat menyimpan benih dan hasil panen.',
                    'url' => route('petani-gudang.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
            if (!\App\Models\Harga::where('user_id', $user->id)->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_harga_petani',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum mengatur Harga Jual Standar Panen. Silakan atur harga terlebih dahulu agar Anda dapat mulai berjualan.',
                    'url' => route('atur-harga.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
        } elseif ($user->role === 'mitra') {
            if (!\App\Models\Gudang::where('user_id', $user->id)->where('jenis_gudang', 'mitra')->exists()) {
                $alerts->push((object)[
                    'id' => 'sys_gudang_mitra',
                    'tipe_notifikasi' => 'system_alert',
                    'pesan' => 'Anda belum memiliki Gudang Mitra. Silakan buat gudang terlebih dahulu agar Anda dapat membeli komoditas dari Koperasi.',
                    'url' => route('mitra-gudang.index'),
                    'is_read' => false,
                    'created_at' => now(),
                    'is_system' => true
                ]);
            }
        }

        return $alerts;
    }
}
