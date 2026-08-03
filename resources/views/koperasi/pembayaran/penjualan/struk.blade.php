<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $payment->id }} - Smart Agroindustri</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
        }
        body {
            background-color: #f3f4f6;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .struk-card {
            background: #fff;
            width: 80mm;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #001842;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-family: sans-serif;
            font-weight: bold;
            border-radius: 6px;
            margin-bottom: 15px;
            cursor: pointer;
            border: none;
        }
        @media print {
            body { background: transparent; padding: 0; }
            .btn-print { display: none; }
            .struk-card { box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>
    <div style="max-width: 80mm; width: 100%;">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Struk (Print)</button>
        
        <div class="struk-card">
            <div class="text-center">
                <h3 class="bold" style="font-size: 14px;">SMART AGROINDUSTRI</h3>
                <p style="font-size: 10px;">Nota Pembayaran Komoditas</p>
            </div>
            
            <div class="divider"></div>
            
            <div class="row">
                <span>No. INV:</span>
                <span class="bold">{{ $payment->kode_inv }}</span>
            </div>
            <div class="row">
                <span>No. TRX:</span>
                <span class="bold">{{ $payment->penjualanBuah->kode_trx ?? ('TRX-' . $payment->penjualan_buah_id) }}</span>
            </div>
            <div class="row">
                <span>Tanggal:</span>
                <span>{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span>Koperasi:</span>
                <span class="bold">{{ substr($payment->penjualanBuah->koperasi->name ?? 'Koperasi', 0, 15) }}</span>
            </div>
            <div class="row">
                <span>Petani:</span>
                <span class="bold">{{ substr($payment->penjualanBuah->pembeli->name ?? 'Petani', 0, 15) }}</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="bold" style="margin-bottom: 4px;">RINCIAN KOMODITAS:</div>
            <div class="row">
                <span>{{ $payment->penjualanBuah->jenisKentang->nama_jenis ?? 'Kentang' }}</span>
            </div>
            <div class="row">
                <span>{{ number_format($payment->penjualanBuah->jumlah_kg ?? 0, 2, ',', '.') }} Kg x Rp {{ number_format(($payment->penjualanBuah->total_harga ?? 0) / max(1, $payment->penjualanBuah->jumlah_kg ?? 1), 0, ',', '.') }}</span>
                <span class="bold">Rp {{ number_format($payment->penjualanBuah->total_harga ?? 0, 0, ',', '.') }}</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="row">
                <span>METODE:</span>
                <span class="bold">
                    @if($payment->metodePembayaran)
                        {{ $payment->metodePembayaran->bank }}
                    @else
                        MIDTRANS / TUNAI
                    @endif
                </span>
            </div>
            <div class="row" style="font-size: 13px;">
                <span class="bold">TOTAL BAYAR:</span>
                <span class="bold">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span>STATUS:</span>
                <span class="bold" style="text-transform: uppercase;">{{ $payment->status }}</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="text-center" style="font-size: 10px; margin-top: 10px;">
                <p class="bold">*** TERIMA KASIH ***</p>
                <p>Simpan Struk Ini Sebagai Bukti Pembayaran Resmi</p>
            </div>
        </div>
    </div>
</body>
</html>
