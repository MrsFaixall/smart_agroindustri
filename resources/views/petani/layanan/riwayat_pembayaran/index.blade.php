@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-teal-900 via-emerald-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-500/30 text-teal-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Layanan Petani</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Riwayat Pembayaran</h1>
            <p class="text-teal-100/80 text-sm max-w-xl">Rekap seluruh pembayaran yang telah Anda terima dari Koperasi atas hasil panen.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Tanggal Pencairan</th>
                    <th class="px-6 py-4">Kode Transaksi (INV)</th>
                    <th class="px-6 py-4">Dari (Koperasi)</th>
                    <th class="px-6 py-4">Metode Diterima</th>
                    <th class="px-6 py-4 text-right">Jumlah Dana</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksis as $t)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($t->tanggal_pembayaran)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600 font-mono">{{ $t->kode_inv }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">🏢 {{ $t->pembelian->koperasi->name ?? '-' }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-600">{{ $t->metodePembayaran->nama_bank ?? '-' }} - {{ $t->metodePembayaran->nomor_rekening ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($t->status === 'lunas')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">✔ Diterima</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">⏳ Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada riwayat pembayaran yang diterima.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->isNotEmpty())
        @include('partials.pagination', ['paginator' => $transaksis, 'label' => 'riwayat pembayaran'])
    @endif
</div>
@endsection
