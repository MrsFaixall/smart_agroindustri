@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-emerald-950 via-slate-900 to-[#001842] p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Traceability & Digital QR</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">E-Label & Pelacakan QR Kentang</h1>
            <p class="text-emerald-100/80 text-sm max-w-xl">Manajemen pelacakan rantai pasok kentang dari Koperasi ke PT Camp / Pasar secara transparan.</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-lg shadow-slate-100/50 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-bold block uppercase tracking-wider">Total QR Aktif</span>
                <span class="text-3xl font-black text-slate-800 font-mono">{{ $qrTransactions->total() }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M4 8h16M4 16h16" /></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-lg shadow-slate-100/50 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-bold block uppercase tracking-wider">Grade Premium</span>
                <span class="text-3xl font-black text-emerald-600 font-mono">
                    {{ $qrTransactions->where('grade', 'Grade A')->count() }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <span class="font-extrabold text-sm outfit">GR-A</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-lg shadow-slate-100/50 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-bold block uppercase tracking-wider">Tujuan PT Camp</span>
                <span class="text-3xl font-black text-indigo-600 font-mono">
                    {{ $qrTransactions->filter(function($t) { return stripos($t->pembeli->name ?? '', 'camp') !== false; })->count() }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs outfit">
                B2B
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <form action="{{ route('koperasi.qr-code.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" 
                    placeholder="Cari token UUID, varietas kentang, atau pembeli...">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-6 py-2.5 text-xs font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    Cari Data
                </button>
                @if(request('search'))
                    <a href="{{ route('koperasi.qr-code.index') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Token QR / SKU</th>
                    <th class="px-6 py-4">Komoditas & Grade</th>
                    <th class="px-6 py-4">Pembeli (Tujuan)</th>
                    <th class="px-6 py-4 text-right">Volume</th>
                    <th class="px-6 py-4">Rute & Estimasi</th>
                    <th class="px-6 py-4 text-center">QR Code</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($qrTransactions as $t)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-lg select-all">{{ substr($t->tracking_token, 0, 8) }}...</span>
                            <button onclick="navigator.clipboard.writeText('{{ $t->tracking_token }}'); alert('Token berhasil disalin!');" 
                                    class="text-slate-400 hover:text-emerald-600 transition" title="Salin Token">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 text-sm block">{{ $t->jenisKentang->nama_jenis ?? '-' }}</span>
                        <span class="inline-block text-[10px] text-emerald-700 bg-emerald-50 font-bold px-1.5 py-0.5 rounded-md mt-0.5">{{ $t->grade ?? 'Grade A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                        🛒 {{ $t->pembeli->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">
                        {{ number_format($t->jumlah_kg, 0, ',', '.') }} Kg
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-600">
                        <div class="space-y-0.5">
                            <span class="block text-slate-800 font-bold">🛣️ Trans Jawa (Tol)</span>
                            <span class="block text-slate-400">Est: {{ $t->estimasi_waktu ?? '6 Jam 15 Menit' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <!-- Mini QR Preview -->
                        <div class="inline-block p-1 border rounded-lg bg-slate-50 hover:scale-105 transition-transform cursor-pointer" 
                             onclick="window.open('{{ route('public.track', $t->tracking_token) }}', '_blank')">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=40x40&data={{ urlencode(route('public.track', $t->tracking_token)) }}" alt="QR" class="w-8 h-8">
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('penjualan-buah.print-qr', $t->id) }}" target="_blank" 
                               class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-all flex items-center gap-1 shadow-sm border border-indigo-100/50">
                                🖨️ Cetak Tag
                            </a>
                            <a href="{{ route('public.track', $t->tracking_token) }}" target="_blank" 
                               class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl text-xs transition-all flex items-center gap-1 shadow-sm border border-emerald-100/50">
                                👁️ Lacak Rute
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada QR Code Logistik aktif.</p>
                        <p class="text-xs text-slate-400 mt-1">Silakan lakukan pencatatan penjualan hasil panen untuk men-generate otomatis.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($qrTransactions->isNotEmpty())
        @include('partials.pagination', ['paginator' => $qrTransactions, 'label' => 'QR Code'])
    @endif
</div>
@endsection
