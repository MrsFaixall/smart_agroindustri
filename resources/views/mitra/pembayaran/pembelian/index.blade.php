@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-blue-950 via-slate-900 to-indigo-950 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                <span>Transaksi Mitra & Penyedia Benih (PT Champ)</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Riwayat Transaksi Mitra</h1>
            <p class="text-slate-300 text-sm max-w-xl">Rekapitulasi tagihan pengadaan benih terstandar dan pembelian hasil panen dari Koperasi.</p>
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg text-slate-800">Daftar Transaksi Kemitraan (B2B)</h2>
                <p class="text-xs text-slate-400">Transaksi pengadaan benih & pasokan kentang industri</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Tipe Transaksi</th>
                        <th class="px-6 py-4">Komoditas</th>
                        <th class="px-6 py-4 text-right">Volume (Kg)</th>
                        <th class="px-6 py-4 text-right">Total Nilai</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pembelians as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-semibold">{{ \Carbon\Carbon::parse($p->tanggal_pembelian ?? $p->tanggal_transaksi)->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                @if(isset($p->tipe_transaksi))
                                    {{ ucwords(str_replace('_', ' ', $p->tipe_transaksi)) }}
                                @else
                                    Pembelian Panen
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $p->jenisKentang->nama_jenis ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">{{ number_format($p->jumlah_kg, 2, ',', '.') }} Kg</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-blue-700">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($p->status === 'lunas')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-bold uppercase tracking-wide">Lunas</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-lg text-xs font-bold uppercase tracking-wide">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">Belum ada catatan transaksi kemitraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $pembelians->links() }}
        </div>
    </div>
</div>
@endsection
