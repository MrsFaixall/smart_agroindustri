@extends('layouts.app')

@section('content')
<div class="space-y-8">
        <x-petani-page-header 
        title="Riwayat Distribusi Benih" 
        subtitle="Rekap seluruh benih yang Anda terima dari Koperasi."
        icon="sparkles"
        color="teal"
    />

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
    <x-petani-table-filter placeholder="Cari data riwayat distribusi benih..." />

            <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Komoditas / Varietas</th>
                    <th class="px-6 py-4">Dari Koperasi</th>
                    <th class="px-6 py-4 text-right">Volume</th>
                    <th class="px-6 py-4 text-right">Total Nilai</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksis as $t)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 text-sm">{{ $t->jenisKentang->nama_jenis ?? '-' }}</span><br>
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ $t->jenisKentang->kategori ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">🏢 {{ $t->koperasi->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">{{ number_format($t->jumlah_kg, 2, ',', '.') }} Kg</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-purple-700 text-right font-mono">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
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
                        <p class="text-sm font-medium">Belum ada riwayat distribusi benih diterima.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->isNotEmpty())
        @include('partials.pagination', ['paginator' => $transaksis, 'label' => 'riwayat distribusi'])
    @endif
</div>
@endsection
