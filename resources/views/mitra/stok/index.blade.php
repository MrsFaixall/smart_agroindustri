@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-emerald-950 via-slate-900 to-emerald-950 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Inventaris & Gudang Mitra</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen Stok Mitra</h1>
            <p class="text-emerald-100/80 text-sm max-w-xl">Monitor ketersediaan stok kentang Anda di Gudang Mitra.</p>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-emerald-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-emerald-800 text-[11px] font-bold tracking-wider uppercase">Total Stok Fisik</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                        📦
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    {{ number_format($stoks->sum('jumlah_stok'), 0, ',', '.') }}
                    <span class="text-sm font-semibold text-slate-400">Kg</span>
                </h3>
            </div>
            <p class="mt-3 text-xs text-emerald-700 font-medium">Stok saat ini di Gudang Mitra</p>
        </div>

        <div class="bg-gradient-to-br from-rose-50/80 via-white to-orange-50/40 border border-rose-200/80 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-rose-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-rose-600 text-[11px] font-bold tracking-wider uppercase">Stok Kritis</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-md shadow-rose-500/20">
                        ⚠️
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-rose-600 tracking-tight">
                    {{ $stoks->where('jumlah_stok', '<', 1000)->count() }} <span class="text-sm font-semibold text-rose-400">Varian</span>
                </h3>
            </div>
            <p class="mt-3 text-xs text-rose-700 font-semibold">Batas kritis di bawah 1.000 Kg</p>
        </div>

        <div class="bg-gradient-to-br from-teal-50/80 via-white to-emerald-50/40 border border-teal-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-teal-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-teal-800 text-[11px] font-bold tracking-wider uppercase">Utilitas Gudang</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-md shadow-teal-500/20">
                        📊
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    {{ $utilitasGudang }}%
                </h3>
            </div>
            <p class="mt-3 text-xs text-teal-700 font-medium">Kapasitas gudang terpakai</p>
        </div>
    </div>

    <!-- Konten Inventaris -->
    <div class="w-full bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/60">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="font-bold text-lg text-slate-800">Daftar Inventaris Mitra</h2>
                <p class="text-xs text-slate-400">Rincian ketersediaan stok fisik kentang dan stok siap jual</p>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Komoditas / Varietas</th>
                        <th class="px-6 py-4">Nama Gudang</th>
                        <th class="px-6 py-4 text-right">Stok Siap Jual (Kg)</th>
                        <th class="px-6 py-4 text-right">Total Stok Fisik (Kg)</th>
                        <th class="px-6 py-4 text-center">Grade</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                @forelse($stoks as $stok)
                    @php
                        if ($stok->grade === 'C') {
                            $status = 'AFKIR';
                            $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                        } elseif ($stok->jumlah_stok <= 0) {
                            $status = 'HABIS';
                            $badge = 'bg-slate-100 text-slate-600 border-slate-200';
                        } else {
                            $status = 'TERSEDIA';
                            $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200 font-bold';
                        }
                    @endphp

                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-sm flex items-center justify-center text-lg font-bold">
                                    🥔
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $stok->jenisKentang->nama_jenis ?? '-' }}</h4>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $stok->jenisKentang->kategori ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                            🏢 {{ $stok->gudang->nama_gudang ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-extrabold text-sm text-teal-700">
                            {{ number_format($stok->stok_dijual, 2, ',', '.') }} Kg
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-1.5">
                                <span class="font-mono font-extrabold text-sm text-slate-800">{{ number_format($stok->jumlah_stok, 2, ',', '.') }} Kg</span>
                                <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $stok->gudang->kapasitas_max > 0 ? min(($stok->jumlah_stok / $stok->gudang->kapasitas_max) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1.5 bg-slate-50 border border-slate-200/80 text-slate-700 rounded-xl text-xs font-bold font-mono">
                                {{ $stok->grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full border text-[10px] uppercase tracking-wider {{ $badge }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <p class="text-sm font-medium">Belum ada data stok kentang di Gudang Mitra Anda.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        
        @if($stoks->isNotEmpty())
            @include('partials.pagination', ['paginator' => $stoks, 'label' => 'stok kentang', 'pageName' => 'stok_page'])
        @endif
    </div>

    <!-- Riwayat Aktivitas Pergerakan Stok -->
    <div class="w-full bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-slate-100/60">
        <div class="border-b border-slate-100 pb-5 mb-5">
            <h3 class="font-extrabold text-lg text-slate-800 tracking-tight">Log Aktivitas Aliran Stok Kentang</h3>
            <p class="text-xs text-slate-400 mt-1">Daftar pergerakan stok kentang masuk (pembelian) dan keluar (penjualan)</p>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($aktivitasStoks as $log)
                <div class="py-3.5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 hover:bg-slate-50/60 px-3 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg {{ $log->type === 'masuk' ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }}">
                            {{ $log->icon }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-bold text-sm text-slate-800">{{ $log->title }}</h4>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $log->badge }}">
                                    {{ $log->badge_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $log->description }}</p>
                        </div>
                    </div>
                    <div class="text-right self-end md:self-center shrink-0">
                        <span class="font-mono font-extrabold text-base {{ $log->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $log->sign }} {{ number_format($log->jumlah_kg, 0, ',', '.') }} Kg
                        </span>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y - H:i') }} WIB
                        </p>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-sm">
                    Belum ada riwayat aktivitas pergerakan stok.
                </div>
            @endforelse
        </div>

        @if($aktivitasStoks->isNotEmpty())
            @include('partials.pagination', ['paginator' => $aktivitasStoks, 'label' => 'log aktivitas', 'pageName' => 'aktivitas_page'])
        @endif
    </div>

</div>
@endsection
