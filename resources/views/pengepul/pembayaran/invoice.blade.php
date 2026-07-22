@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Action Bar (Hidden when printing) -->
    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('pembayaran.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Transaksi
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('pembayaran.struk', $payment->id) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Cetak Struk Ringkas (80mm)
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-[#001842] px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-[#002a70] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Invoice / PDF
            </button>
        </div>
    </div>

    <!-- Invoice Card Document -->
    <div id="invoice-printable" class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 shadow-xl print:shadow-none print:border-none print:p-0">
        
        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200 pb-8 gap-6">
            <div>
                <div class="flex items-center gap-3">
                    <div class="bg-[#001842] text-white p-3 rounded-2xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-[#001842] tracking-tight uppercase">Smart Agroindustri</h1>
                        <p class="text-xs font-semibold text-slate-500 tracking-wide uppercase">Sistem Pengelolaan Komoditas Kentang</p>
                    </div>
                </div>
            </div>
            <div class="text-left md:text-right">
                <h2 class="text-xl font-bold font-mono text-slate-900">{{ $payment->kode_inv }}</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Ref TRX: {{ $payment->pembelian->kode_trx ?? ('TRX-' . $payment->pembelian_id) }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Tanggal: {{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d F Y - H:i') }} WIB</p>
            </div>
        </div>

        <!-- Meta Grid Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-8 border-b border-slate-100">
            <!-- Pengepul (Pembeli) -->
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengepul / Pembeli</p>
                <h3 class="text-base font-bold text-slate-900">{{ $payment->pembelian->pengepul->name ?? 'Pengepul' }}</h3>
                <p class="text-xs text-slate-500">{{ $payment->pembelian->pengepul->email ?? '-' }}</p>
            </div>
            <!-- Petani (Penjual) -->
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemasok / Petani</p>
                <h3 class="text-base font-bold text-slate-900">{{ $payment->pembelian->petani->name ?? 'Petani' }}</h3>
                <p class="text-xs text-slate-500">{{ $payment->pembelian->petani->email ?? '-' }}</p>
            </div>
            <!-- Status Pembayaran -->
            <div class="space-y-1 md:text-right">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Pembayaran</p>
                <div>
                    @if($payment->status === 'lunas' || $payment->status === 'berhasil')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            LUNAS
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            MENUNGGU PEMBAYARAN
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            BELUM LUNAS
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table Details -->
        <div class="py-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Rincian Komoditas</h3>
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4">ID Pesanan</th>
                            <th class="px-6 py-4">Komoditas / Jenis Kentang</th>
                            <th class="px-6 py-4 text-right">Volume (Kg)</th>
                            <th class="px-6 py-4 text-right">Harga / Kg</th>
                            <th class="px-6 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">#{{ $payment->pembelian_id }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $payment->pembelian->jenisKentang->nama_jenis ?? 'Kentang' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono">{{ number_format($payment->pembelian->jumlah_kg ?? 0, 2, ',', '.') }} Kg</td>
                            <td class="px-6 py-4 text-right font-mono">
                                Rp {{ number_format(($payment->pembelian->total_harga ?? 0) / max(1, $payment->pembelian->jumlah_kg ?? 1), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold font-mono text-slate-900">
                                Rp {{ number_format($payment->pembelian->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Calculation & Payment Method Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <!-- Information Method -->
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 space-y-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Metode Pembayaran</p>
                @if($payment->snap_token && !$payment->metode_pembayaran_id)
                    <p class="text-sm font-bold text-slate-800">Payment Gateway Midtrans</p>
                    <p class="text-xs text-slate-500 font-mono">Tipe: {{ strtoupper(str_replace('_', ' ', $payment->payment_type ?? 'QRIS / VA')) }}</p>
                @elseif($payment->metodePembayaran)
                    <p class="text-sm font-bold text-slate-800">{{ $payment->metodePembayaran->kategori ?? 'Transfer Bank' }} - {{ $payment->metodePembayaran->bank }}</p>
                    <p class="text-xs font-mono text-slate-600">No. Rekening: {{ $payment->metodePembayaran->no_rekening }}</p>
                    <p class="text-xs text-slate-500">Atas Nama: {{ $payment->metodePembayaran->atas_nama }}</p>
                @else
                    <p class="text-sm font-bold text-slate-800">Tunai / Transfer Direct</p>
                @endif
            </div>

            <!-- Total Amount Card -->
            <div class="space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Total Harga Pembelian:</span>
                    <span class="font-bold font-mono text-slate-800">Rp {{ number_format($payment->pembelian->total_harga ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm pt-2 border-t border-slate-100">
                    <span class="text-slate-500 font-medium">Jumlah Dibayar:</span>
                    <span class="font-bold font-mono text-emerald-600 text-lg">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
                @if(($payment->pembelian->total_harga ?? 0) - $payment->jumlah_bayar > 0)
                <div class="flex justify-between items-center text-xs text-amber-700 bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                    <span class="font-semibold">Sisa Pembayaran:</span>
                    <span class="font-bold font-mono">Rp {{ number_format(($payment->pembelian->total_harga ?? 0) - $payment->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-12 pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs text-slate-500">
            <div>
                <p class="font-semibold mb-12">Pihak Pemasok / Petani</p>
                <p class="font-bold text-slate-800 uppercase">( {{ $payment->pembelian->petani->name ?? 'Petani' }} )</p>
            </div>
            <div>
                <p class="font-semibold mb-12">Pihak Pengepul / Pembeli</p>
                <p class="font-bold text-slate-800 uppercase">( {{ $payment->pembelian->pengepul->name ?? 'Pengepul' }} )</p>
            </div>
        </div>

        <div class="mt-8 text-center text-[11px] text-slate-400 border-t border-slate-100 pt-4">
            <p>Terima kasih atas transaksi Anda. Dokumen ini sah dan diterbitkan secara otomatis oleh Sistem Smart Agroindustri.</p>
        </div>

    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-printable, #invoice-printable * {
        visibility: visible;
    }
    #invoice-printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endsection
