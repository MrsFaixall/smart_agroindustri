@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-emerald-950 via-teal-900 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Manajemen Harga Jual Pasar (Koperasi)</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Atur Harga Pasar</h1>
            <p class="text-emerald-100/80 text-sm max-w-xl">Tetapkan harga jual komoditas (Benih / Buah Kentang) dari Koperasi ke PT Champ / Konsumen.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('koperasi.atur-harga-pasar.create') }}" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-emerald-600/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Atur Harga Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" /> {{ session('success') }}
        </div>
    @endif

    <!-- Stat Cards -->
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-blue-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-slate-500 text-[11px] font-bold tracking-wider uppercase">Rata-rata Harga Jual</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20">
                        <x-heroicon-o-calculator class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Rp {{ number_format($summary['rata_rata'], 0, ',', '.') }}</h3>
            </div>
            <p class="mt-3 text-xs text-slate-400 font-medium">Rata-rata seluruh varietas</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-emerald-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-emerald-800 text-[11px] font-bold tracking-wider uppercase">Harga Jual Tertinggi</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                        <x-heroicon-o-arrow-trending-up class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-emerald-900 tracking-tight">Rp {{ number_format($summary['tertinggi']->harga ?? 0, 0, ',', '.') }}</h3>
            </div>
            <p class="mt-3 truncate text-xs text-emerald-700 font-medium">{{ $summary['tertinggi']->jenisKentang->nama_jenis ?? 'Belum ada data' }}</p>
        </div>

        <div class="bg-gradient-to-br from-rose-50/80 via-white to-red-50/40 border border-rose-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-rose-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-rose-800 text-[11px] font-bold tracking-wider uppercase">Harga Jual Terendah</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-md shadow-rose-500/20">
                        <x-heroicon-o-arrow-trending-down class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-rose-900 tracking-tight">Rp {{ number_format($summary['terendah']->harga ?? 0, 0, ',', '.') }}</h3>
            </div>
            <p class="mt-3 truncate text-xs text-rose-700 font-medium">{{ $summary['terendah']->jenisKentang->nama_jenis ?? 'Belum ada data' }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40 border border-purple-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-purple-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-purple-800 text-[11px] font-bold tracking-wider uppercase">Komoditas Dipantau</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-md shadow-purple-500/20">
                        <x-heroicon-o-tag class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-purple-900 tracking-tight">{{ $summary['total'] }} <span class="text-sm font-semibold text-purple-600">Varian</span></h3>
            </div>
            <p class="mt-3 text-xs text-purple-700 font-medium">Harga Khusus Konsumen / PT Champ</p>
        </div>
    </div>

    <!-- Layout Main Table -->
    <div class="w-full bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="font-bold text-lg text-slate-800">Daftar Harga Jual Pasar Koperasi</h2>
                <p class="text-xs text-slate-400">Daftar harga jual per kilogram ke konsumen/mitra</p>
            </div>
            <span class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-800 uppercase tracking-wider">{{ $summary['total'] }} KOMODITAS</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Nama Komoditas</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-right">Harga Jual Koperasi (Rp/Kg)</th>
                        <th class="px-6 py-4 text-center">Terakhir Diperbarui</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($prices as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $p->jenisKentang->nama_jenis ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($p->jenisKentang && $p->jenisKentang->kategori === 'benih')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-md text-[10px] font-bold uppercase tracking-wider">Benih (Hulu)</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded-md text-[10px] font-bold uppercase tracking-wider">Kentang Konsumsi (Hilir)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-emerald-700">Rp {{ number_format($p->harga, 0, ',', '.') }} / Kg</td>
                            <td class="px-6 py-4 text-center text-slate-400 font-semibold">{{ $p->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('koperasi.atur-harga-pasar.edit', $p->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition-colors">Edit</a>
                                    <form action="{{ route('koperasi.atur-harga-pasar.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengaturan harga untuk komoditas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded-lg text-xs transition-colors">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">Belum ada harga jual yang ditetapkan oleh Koperasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
