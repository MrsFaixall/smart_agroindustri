<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Setup configuration Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelians,id',
            'jumlah_bayar' => 'required|numeric|min:0.01',
        ]);

        $pembelian = Pembelian::with('petani')->find($request->pembelian_id);

        // Generate unique order ID
        $orderId = 'TRX-' . $pembelian->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->jumlah_bayar,
            ],
            'customer_details' => [
                'first_name' => $pembelian->petani->name ?? 'Petani',
                'email' => $pembelian->petani->email ?? 'petani@example.com',
                'phone' => $pembelian->petani->no_hp ?? '080000000000',
            ],
        ];
        
        if ($request->filled('payment_type') && $request->payment_type !== 'midtrans') {
            $params['enabled_payments'] = [$request->payment_type];
        }

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan record pembayaran sementara (pending)
            Pembayaran::create([
                'pembelian_id' => $pembelian->id,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tanggal_pembayaran' => now(),
                'status' => 'pending',
                'snap_token' => $snapToken,
                'midtrans_order_id' => $orderId,
                // metode_pembayaran_id dibiarkan null
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        $notif = new Notification();

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;
        $paymentType = $notif->payment_type;

        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $pembayaran->midtrans_transaction_id = $notif->transaction_id;
        $pembayaran->payment_type = $paymentType;
        
        if (isset($notif->pdf_url)) {
            $pembayaran->pdf_url = $notif->pdf_url;
        }

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                    $pembayaran->status = 'pending';
                } else {
                    $pembayaran->status = 'lunas';
                    $this->updatePembelianDanStok($pembayaran);
                }
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $pembayaran->status = 'gagal'; // atau bisa dibiarkan pending/dihapus
            } else if ($transactionStatus == 'pending') {
                $pembayaran->status = 'pending';
            }

            $pembayaran->save();
            DB::commit();

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Midtrans Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function updatePembelianDanStok($pembayaran)
    {
        $pembelian = Pembelian::find($pembayaran->pembelian_id);
        
        // Hitung total pembayaran sukses
        $totalDibayar = Pembayaran::where('pembelian_id', $pembelian->id)
            ->whereIn('status', ['lunas', 'berhasil', 'sukses'])
            ->sum('jumlah_bayar');

        // Tambahkan dengan pembayaran saat ini jika statusnya lunas (karena $pembayaran belum disave di DB jika dipanggil dari transaction block)
        // Wait, $totalDibayar include current if it's already in DB, but we query from DB. 
        // We can just add the current payment's amount to avoid timing issues if it's not saved yet, OR we save it before.
        // Actually, let's just make sure we account for it. 
        // Let's sum from DB excluding current one, then add current one.
        $totalDibayarLain = Pembayaran::where('pembelian_id', $pembelian->id)
            ->where('id', '!=', $pembayaran->id)
            ->whereIn('status', ['lunas', 'berhasil', 'sukses'])
            ->sum('jumlah_bayar');
            
        $totalSemuaDibayar = $totalDibayarLain + $pembayaran->jumlah_bayar;

        if ($totalSemuaDibayar >= $pembelian->total_harga && $pembelian->status !== 'lunas') {
            // Verifikasi stok
            $totalStok = Stok::where('jenis_kentang_id', $pembelian->jenis_kentang_id)->sum('jumlah_stok');
            if ($pembelian->jumlah_kg > $totalStok) {
                throw new \Exception('Stok tidak mencukupi untuk melunasi transaksi pembelian ini. Sisa stok: ' . $totalStok);
            }

            // Kurangi stok FIFO
            $jumlah_dibeli = $pembelian->jumlah_kg;
            $stoks = Stok::where('jenis_kentang_id', $pembelian->jenis_kentang_id)
                ->where('jumlah_stok', '>', 0)
                ->orderBy('id')
                ->get();
                
            foreach ($stoks as $stok) {
                if ($jumlah_dibeli <= 0) break;
                
                if ($stok->jumlah_stok >= $jumlah_dibeli) {
                    $stok->jumlah_stok -= $jumlah_dibeli;
                    $stok->save();
                    $jumlah_dibeli = 0;
                } else {
                    $jumlah_dibeli -= $stok->jumlah_stok;
                    $stok->jumlah_stok = 0;
                    $stok->save();
                }
            }

            $pembelian->update(['status' => 'lunas']);
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        
        $pembayaran = Pembayaran::with('pembelian')->where('midtrans_order_id', $orderId)->first();
        
        if ($pembayaran) {
            try {
                // Konfigurasi ulang jika belum di-set secara global
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production');
                
                $status_response = \Midtrans\Transaction::status($orderId);
                $transactionStatus = $status_response->transaction_status;
                $fraudStatus = $status_response->fraud_status ?? null;
                
                DB::beginTransaction();
                
                $pembayaran->midtrans_transaction_id = $status_response->transaction_id ?? $pembayaran->midtrans_transaction_id;
                $pembayaran->payment_type = $status_response->payment_type ?? $pembayaran->payment_type;
                
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                        $pembayaran->status = 'pending';
                    } else {
                        if ($pembayaran->status !== 'lunas') {
                            $pembayaran->status = 'lunas';
                            $this->updatePembelianDanStok($pembayaran);
                        }
                    }
                } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $pembayaran->status = 'gagal';
                } else if ($transactionStatus == 'pending') {
                    $pembayaran->status = 'pending';
                }

                $pembayaran->save();
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Midtrans Status Check Error: ' . $e->getMessage());
            }
        }
        
        $transactionStatus = $pembayaran->status ?? 'pending';

        return view('pengepul.pembayaran.finish', compact('pembayaran', 'transactionStatus', 'orderId'));
    }
}
