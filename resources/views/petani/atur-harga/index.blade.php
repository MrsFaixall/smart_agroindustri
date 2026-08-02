@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-amber-900 via-orange-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-orange-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Manajemen Harga Acuan</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Atur Harga Petani</h1>
            <p class="text-amber-100/80 text-sm max-w-xl">Monitor harga acuan pasaran kentang dari Koperasi, dan atur harga kentang Anda sendiri.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('atur-harga.create') }}" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-amber-500/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Input Harga Baru
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
                    <p class="text-slate-500 text-[11px] font-bold tracking-wider uppercase">Rata-rata Harga</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20">
                        <x-heroicon-o-calculator class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Rp {{ number_format($summary['rata_rata'], 0, ',', '.') }}</h3>
            </div>
            <p class="mt-3 text-xs text-slate-400 font-medium">Rata-rata seluruh komoditas</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 border border-amber-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-amber-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-amber-800 text-[11px] font-bold tracking-wider uppercase">Harga Tertinggi</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20">
                        <x-heroicon-o-arrow-trending-up class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-amber-900 tracking-tight">Rp {{ number_format($summary['tertinggi']->harga ?? 0, 0, ',', '.') }}</h3>
            </div>
            <p class="mt-3 truncate text-xs text-amber-700 font-medium">{{ $summary['tertinggi']->jenisKentang->nama_jenis ?? 'Belum ada data' }}</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-emerald-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-emerald-800 text-[11px] font-bold tracking-wider uppercase">Harga Terendah</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                        <x-heroicon-o-arrow-trending-down class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-emerald-900 tracking-tight">Rp {{ number_format($summary['terendah']->harga ?? 0, 0, ',', '.') }}</h3>
            </div>
            <p class="mt-3 truncate text-xs text-emerald-700 font-medium">{{ $summary['terendah']->jenisKentang->nama_jenis ?? 'Belum ada data' }}</p>
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
            <p class="mt-3 text-xs text-purple-700 font-medium">Terakhir diperbarui hari ini</p>
        </div>
    </div>

    <!-- Layout Main Table & Chart -->
    <div class="grid gap-6 xl:grid-cols-5">
        <div class="space-y-6 xl:col-span-3">
            <!-- Table 1: Harga Pasar -->
            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60 mb-6">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-slate-50/50">
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Daftar Harga Pasar (Koperasi)</h2>
                        <p class="text-xs text-slate-400">Harga acuan komoditas yang diatur oleh Koperasi</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4">Komoditas</th>
                                <th class="px-6 py-4">Kualitas</th>
                                <th class="px-6 py-4 text-right">Harga Patokan / Kg</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($hargaPasars as $pasar)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 text-white shadow-sm font-bold">
                                            <x-heroicon-o-building-storefront class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $pasar->jenisKentang->nama_jenis ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $pasar->jenisKentang->kualitas ?? 'Standar' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-base font-extrabold text-blue-700">
                                    Rp {{ number_format($pasar->harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center">
                                    <x-heroicon-o-information-circle class="mx-auto h-12 w-12 text-slate-300" />
                                    <p class="mt-3 text-sm font-medium text-slate-500">Belum ada harga acuan dari Koperasi.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 2: Harga Petani -->
            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Harga Jual Anda</h2>
                        <p class="text-xs text-slate-400">Harga jual kentang yang Anda atur sendiri</p>
                    </div>
                    <span class="rounded-xl bg-amber-50 border border-amber-200/60 px-3 py-1 text-[11px] font-bold text-amber-800 uppercase tracking-wider">{{ $prices->count() }} HARGA</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Komoditas</th>
                                <th class="px-6 py-4">Harga Anda / Kg</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($prices as $price)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-sm font-bold">
                                            <x-heroicon-o-archive-box class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $price->jenisKentang->nama_jenis ?? '-' }}</p>
                                            <p class="text-xs text-slate-400">{{ $price->jenisKentang->kualitas ?? 'Komoditas kentang' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-base font-extrabold text-amber-900">
                                        Rp {{ number_format($price->harga, 0, ',', '.') }}
                                    </div>
                                    @php
                                        $pasar = $hargaPasars->get($price->jenis_kentang_id);
                                        $hargaPasarVal = $pasar ? $pasar->harga : 0;
                                        $diff = $price->harga - $hargaPasarVal;
                                    @endphp
                                    @if($hargaPasarVal > 0 && $diff != 0)
                                        <div class="text-[10px] font-bold mt-1 {{ $diff > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $diff > 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }} dari patokan koperasi
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('atur-harga.edit', $price) }}" class="rounded-xl bg-blue-50 px-3.5 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-all">Edit</a>
                                        <form action="{{ route('atur-harga.destroy', $price) }}" method="POST" onsubmit="return confirm(@js('Hapus harga Anda untuk '.($price->jenisKentang->nama_jenis ?? '').'?'))">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="rounded-xl bg-rose-50 px-3.5 py-2 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-all">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center">
                                    <x-heroicon-o-tag class="mx-auto h-12 w-12 text-slate-300" />
                                    <p class="mt-3 text-sm font-medium text-slate-500">Anda belum mengatur harga jual komoditas apapun.</p>
                                    <a href="{{ route('atur-harga.create') }}" class="mt-2 inline-block text-sm font-bold text-amber-600 hover:underline">Input harga pertama</a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-xl shadow-slate-100/60">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Perbandingan Harga Komoditas</h2>
                        <p class="text-xs text-slate-400">Visualisasi harga acuan per kilogram</p>
                    </div>
                    <span class="rounded-full bg-amber-500/10 border border-amber-200 text-amber-700 px-3 py-1 text-[10px] font-bold">SAAT INI</span>
                </div>
                <div class="h-64"><canvas id="hargaChart"></canvas></div>
            </div>
        </div>

        <aside class="flex min-h-[400px] flex-col overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60 xl:col-span-2">
            <div class="border-b border-slate-100 p-6">
                <h2 class="font-bold text-lg text-slate-800">Aktivitas Harga Terkini</h2>
                <p class="text-xs text-slate-400">Daftar harga yang terakhir diperbarui</p>
            </div>
            <div class="flex-1 divide-y divide-slate-100">
                @forelse($prices->take(6) as $price)
                    <div class="flex gap-3.5 p-5 hover:bg-slate-50/60 transition-colors">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-xs font-bold text-white shadow-md shadow-amber-500/20">
                            {{ strtoupper(substr($price->jenisKentang->nama_jenis ?? 'K', 0, 2)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between gap-2">
                                <p class="truncate text-sm font-bold text-slate-800">{{ $price->jenisKentang->nama_jenis ?? '-' }}</p>
                                <time class="shrink-0 text-[10px] font-medium text-slate-400">{{ $price->updated_at?->diffForHumans() }}</time>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Diperbarui: <span class="font-extrabold text-amber-800">Rp {{ number_format($price->harga, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                @empty
                    <p class="p-8 text-center text-sm text-slate-400">Belum ada aktivitas harga.</p>
                @endforelse
            </div>
            <div class="border-t border-slate-100 p-4 text-center text-xs text-slate-400">Aktivitas berdasarkan pembaruan harga terakhir</div>
        </aside>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chart = document.getElementById('hargaChart');
    if (!chart) return;
    new Chart(chart, { 
        type: 'bar', 
        data: { 
            labels: @json($prices->pluck('jenisKentang.nama_jenis')), 
            datasets: [{ 
                label: 'Harga / Kg', 
                data: @json($prices->pluck('harga')), 
                backgroundColor: '#f59e0b', 
                hoverBackgroundColor: '#d97706',
                borderRadius: 10, 
                maxBarThickness: 48 
            }] 
        }, 
        options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            plugins: { 
                legend: { display: false }, 
                tooltip: { 
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: { 
                        label: context => 'Rp ' + context.raw.toLocaleString('id-ID') + ' / Kg' 
                    } 
                } 
            }, 
            scales: { 
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#64748b' } }, 
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#64748b', callback: value => 'Rp ' + value.toLocaleString('id-ID') } } 
            } 
        } 
    });
});
</script>
@endsection
