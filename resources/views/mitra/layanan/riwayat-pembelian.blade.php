@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-emerald-950 via-teal-900 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Layanan Mitra</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Riwayat Pembelian Kentang</h1>
            <p class="text-emerald-100/80 text-sm max-w-xl">Rekap seluruh riwayat transaksi pembelian kentang dari Koperasi.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Tanggal Pembelian</th>
                    <th class="px-6 py-4">Koperasi Penjual</th>
                    <th class="px-6 py-4">Jenis Kentang</th>
                    <th class="px-6 py-4 text-right">Jumlah (Kg)</th>
                    <th class="px-6 py-4 text-right">Total Harga</th>
                    <th class="px-6 py-4 text-center">Status Pembayaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksis as $t)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">🏢 {{ $t->koperasi->name ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $t->jenisKentang->nama_jenis ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">{{ number_format($t->jumlah_kg, 2, ',', '.') }} Kg</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($t->status === 'lunas')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">✔ Lunas</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">⏳ Belum Lunas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada riwayat pembelian kentang.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->isNotEmpty())
        @include('partials.pagination', ['paginator' => $transaksis, 'label' => 'riwayat pembelian'])
    @endif
</div>
@endsection
