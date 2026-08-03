@extends('layouts.app')

@section('content')
@php
    $isPetani = auth()->user()->role === 'petani';
@endphp

<div class="space-y-8">
        <x-petani-page-header 
        title="Distribusi Benih (Penerimaan)" 
        subtitle="Catatan distribusi atau pembelian benih yang Anda terima dari Koperasi."
        icon="sparkles"
        color="teal"
        bgImage="bg-benih.jpg"
        actionUrl="{{ route('distribusi-benih.create') }}"
        actionText="Tambah Distribusi"
        actionIcon="plus"
    />

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <form action="{{ route('distribusi-benih.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" 
                    placeholder="Cari varietas benih atau nama petani...">
            </div>

            <div class="flex items-center gap-1.5 min-w-[260px]">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                <span class="text-xs text-slate-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-xs font-bold rounded-2xl text-white bg-purple-600 hover:bg-purple-700 transition-all shadow-md shadow-purple-600/20">
                    Filter
                </button>
                @if(request('search') || request('start_date') || request('end_date'))
                    <a href="{{ route('distribusi-benih.index') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
    <x-petani-table-filter placeholder="Cari data distribusi benih (penerimaan)..." />

            <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Komoditas / Varietas</th>
                    <th class="px-6 py-4">Koperasi Penyalur</th>
                    <th class="px-6 py-4 text-right">Volume</th>
                    <th class="px-6 py-4 text-right">Total Nilai</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    @if(!$isPetani)
                    <th class="px-6 py-4 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksis as $t)
                <tr class="hover:bg-slate-50/50 transition-colors group">
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
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">Lunas</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">Belum Lunas</span>
                        @endif
                    </td>
                    @if(!$isPetani)
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($t->status === 'belum lunas')
                                <form action="{{ route('distribusi-benih.bayar', $t->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pelunasan transaksi ini?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">Bayar / Lunasi</button>
                                </form>
                            @endif
                            <form action="{{ route('distribusi-benih.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs transition-all">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $isPetani ? 6 : 7 }}" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada catatan transaksi distribusi benih.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->isNotEmpty())
        @include('partials.pagination', ['paginator' => $transaksis, 'label' => 'transaksi distribusi'])
    @endif
</div>
@endsection
