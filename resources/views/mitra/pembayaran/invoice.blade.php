@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 print:hidden">
        <a href="{{ route('mitra.pembayaran.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2 text-sm font-semibold">
            ← Kembali ke Daftar Transaksi
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('mitra.pembayaran.struk', $payment->id) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                🖨️ Cetak Struk (80mm)
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-slate-900 to-emerald-950 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-slate-900/20 hover:from-slate-800 hover:to-emerald-900 transition-all">
                📄 Cetak Invoice / PDF
            </button>
        </div>
    </div>

    <!-- Invoice Card Document -->
    <div id="invoice-printable" class="bg-white rounded-3xl border border-slate-100 p-8 md:p-12 shadow-2xl shadow-slate-200/50 relative overflow-hidden print:shadow-none print:border-none print:p-0">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-blue-600 absolute top-0 left-0"></div>

        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-8 gap-6 pt-2">
            <div>
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-emerald-950 to-slate-900 text-white p-3 rounded-2xl shadow-md">
                        🥔
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Smart Agroindustri</h1>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sistem Pengelolaan Komoditas Kentang</p>
                    </div>
                </div>
            </div>
            <div class="text-left md:text-right">
                <h2 class="text-xl font-extrabold font-mono text-emerald-950 tracking-tight">INV-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-xs text-slate-400 font-semibold mt-0.5">Ref TRX: INV-{{ str_pad($payment->penjualan_buah_id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-slate-400 font-medium mt-1">Tanggal: {{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d F Y - H:i') }} WIB</p>
            </div>
        </div>

        <!-- Meta Grid Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-8 border-b border-slate-100">
            <!-- Mitra (Pembeli) -->
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mitra / Pembeli</p>
                <h3 class="text-base font-extrabold text-slate-800">{{ $payment->penjualanBuah->pembeli->name ?? 'Mitra' }}</h3>
                <p class="text-xs text-slate-400 font-medium">{{ $payment->penjualanBuah->pembeli->email ?? '-' }}</p>
            </div>
            <!-- Koperasi (Penjual) -->
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Penerima / Koperasi</p>
                <h3 class="text-base font-extrabold text-slate-800">{{ $payment->penjualanBuah->koperasi->name ?? 'Koperasi' }}</h3>
                <p class="text-xs text-slate-400 font-medium">{{ $payment->penjualanBuah->koperasi->email ?? '-' }}</p>
            </div>
            <!-- Status Pembayaran -->
            <div class="space-y-1 md:text-right">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Pembayaran</p>
                <div>
                    @if($payment->status === 'lunas' || $payment->status === 'berhasil')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                            ✓ LUNAS
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-extrabold bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                            ⏳ PROSES VERIFIKASI
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table Details -->
        <div class="py-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Rincian Komoditas</h3>
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-slate-400 font-bold text-[11px] uppercase tracking-wider border-b border-slate-100">
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
                            <td class="px-6 py-4 font-mono font-bold text-emerald-950">#{{ $payment->penjualan_buah_id }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $payment->penjualanBuah->jenisKentang->nama_jenis ?? 'Kentang' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-slate-700">{{ number_format($payment->penjualanBuah->jumlah_kg ?? 0, 2, ',', '.') }} Kg</td>
                            <td class="px-6 py-4 text-right font-mono text-slate-700">
                                Rp {{ number_format(($payment->penjualanBuah->total_harga ?? 0) / max(1, $payment->penjualanBuah->jumlah_kg ?? 1), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold font-mono text-slate-900">
                                Rp {{ number_format($payment->penjualanBuah->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Calculation & Payment Method Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <!-- Information Method -->
            <div class="bg-slate-50/80 p-5 rounded-2xl border border-slate-100 space-y-2">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Metode Pembayaran</p>
                @if($payment->metodePembayaran)
                    <p class="text-sm font-bold text-slate-800">{{ $payment->metodePembayaran->kategori ?? 'Transfer Bank' }} — {{ $payment->metodePembayaran->bank }}</p>
                    <p class="text-xs font-mono font-bold text-emerald-700">No. Rekening: {{ $payment->metodePembayaran->no_rekening }}</p>
                    <p class="text-xs text-slate-400 font-medium">Atas Nama: {{ $payment->metodePembayaran->atas_nama }}</p>
                @else
                    <p class="text-sm font-bold text-slate-800">Direct / Manual Transfer</p>
                @endif
                @if($payment->catatan)
                    <p class="text-xs text-slate-400 font-medium italic mt-2">Catatan: {{ $payment->catatan }}</p>
                @endif
            </div>

            <!-- Total Amount Card -->
            <div class="space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Total Harga Pembelian:</span>
                    <span class="font-bold font-mono text-slate-800">Rp {{ number_format($payment->penjualanBuah->total_harga ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm pt-2 border-t border-slate-100">
                    <span class="text-slate-500 font-medium">Jumlah Dibayar:</span>
                    <span class="font-extrabold font-mono text-emerald-700 text-lg">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-12 pt-8 border-t border-slate-100 grid grid-cols-2 gap-8 text-center text-xs text-slate-500">
            <div>
                <p class="font-semibold mb-12">Pihak Mitra / Pembeli</p>
                <p class="font-bold text-slate-800 uppercase">( {{ $payment->penjualanBuah->pembeli->name ?? 'Mitra' }} )</p>
            </div>
            <div>
                <p class="font-semibold mb-12">Pihak Koperasi / Penjual</p>
                <p class="font-bold text-slate-800 uppercase">( {{ $payment->penjualanBuah->koperasi->name ?? 'Koperasi' }} )</p>
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
