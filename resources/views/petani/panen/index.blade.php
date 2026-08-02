@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ searchQuery: '' }">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-emerald-900 via-teal-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Pencatatan & Logistik Panen</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen Panen</h1>
            <p class="text-emerald-100/80 text-sm max-w-xl">Kelola pencatatan dan pantau status hasil panen komoditas musim ini.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('panen.create') }}" class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-emerald-500/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Catat Hasil Panen
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" /> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-4 text-sm font-semibold text-rose-800 shadow-sm">
            <x-heroicon-o-x-circle class="h-5 w-5 text-rose-600" /> {{ session('error') }}
        </div>
    @endif

    @if($hasNoGudang)
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-amber-600 to-orange-600 p-6 rounded-3xl text-white shadow-xl shadow-amber-200/50">
            <div>
                <h4 class="font-extrabold text-base">⚠️ Anda Belum Memiliki Gudang</h4>
                <p class="text-xs text-amber-50 mt-1">Silakan buat gudang penyimpanan terlebih dahulu agar hasil panen Anda dapat disimpan dan tercatat di sistem.</p>
            </div>
            <a href="{{ route('petani-gudang.create') }}" class="bg-white hover:bg-slate-50 text-amber-700 px-4 py-2 rounded-xl text-xs font-bold shadow transition-all">
                Buat Gudang Sekarang
            </a>
        </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Card 1: Total Musim Ini -->
        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-emerald-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-emerald-800 text-[11px] font-bold tracking-wider uppercase">Total Musim Ini</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                        <x-heroicon-o-archive-box class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    {{ number_format(($totalMusimIni ?? 0) / 1000, 1, ',', '.') }} <span class="text-sm font-semibold text-slate-400">Ton</span>
                </h3>
            </div>
            <p class="mt-3 text-xs text-emerald-700 font-medium">{{ number_format($totalMusimIni ?? 0, 0, ',', '.') }} Kg tercatat</p>
        </div>

        <!-- Card 2: Harga Pasar -->
        <div class="bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 border border-amber-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-amber-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-amber-800 text-[11px] font-bold tracking-wider uppercase">Harga Pasar</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20">
                        <x-heroicon-o-currency-dollar class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-amber-900 tracking-tight">
                    Rp {{ number_format($hargaPasar ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <p class="mt-3 text-xs text-amber-700 font-medium">/kg rata-rata acuan</p>
        </div>

        <!-- Card 3: Menunggu Bayar -->
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-blue-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-blue-800 text-[11px] font-bold tracking-wider uppercase">Menunggu Bayar</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20">
                        <x-heroicon-o-clock class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    @if(($menungguBayar ?? 0) >= 1000000)
                        Rp {{ number_format(($menungguBayar ?? 0) / 1000000, 1, ',', '.') }}Jt
                    @else
                        Rp {{ number_format($menungguBayar ?? 0, 0, ',', '.') }}
                    @endif
                </h3>
            </div>
            <p class="mt-3 text-xs text-blue-700 font-medium">Tagihan belum lunas</p>
        </div>

        <!-- Card 4: Kapasitas Gudang -->
        <div class="bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40 border border-purple-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative flex flex-col justify-between"
             x-data="{ 
                 selectedGudangId: {{ isset($gudangs) && $gudangs->isNotEmpty() ? $gudangs->first()->id : 'null' }},
                 gudangs: {{ json_encode(($gudangs ?? collect())->map(function($g) {
                     $terpakai = $g->kapasitas_terpakai;
                     $max = $g->kapasitas_max;
                     $sisa = $max - $terpakai;
                     $persen = $max > 0 ? round(($terpakai / $max) * 100) : 0;
                     return [
                         'id' => $g->id,
                         'nama' => $g->nama_gudang,
                         'terpakai' => $terpakai,
                         'max' => $max,
                         'sisa' => $sisa,
                         'persen' => $persen,
                     ];
                 })->values()) }}
             }">
            @if(isset($gudangs) && $gudangs->isNotEmpty())
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-purple-800 text-[11px] font-bold tracking-wider uppercase">Kapasitas Gudang</p>
                        @if($gudangs->count() > 1)
                            <select x-model="selectedGudangId" class="text-[11px] font-bold text-purple-900 bg-white border border-purple-200 rounded-lg px-2 py-0.5 focus:outline-none max-w-[130px] truncate shadow-2xs">
                                <template x-for="g in gudangs" :key="g.id">
                                    <option :value="g.id" x-text="g.nama"></option>
                                </template>
                            </select>
                        @endif
                    </div>
                    
                    <template x-for="g in gudangs" :key="g.id">
                        <div x-show="selectedGudangId == g.id" class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-purple-900 uppercase max-w-[140px] truncate" x-text="g.nama"></span>
                                <span class="text-xs font-bold" :class="g.persen >= 100 ? 'text-rose-600 font-black' : 'text-purple-900'" x-text="g.persen + '%'"></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-300"
                                     :class="g.persen >= 100 ? 'bg-rose-500' : (g.persen >= 80 ? 'bg-amber-500' : 'bg-gradient-to-r from-purple-500 to-indigo-600')"
                                     :style="'width: ' + Math.min(100, g.persen) + '%'"></div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <template x-for="g in gudangs" :key="'sisa-' + g.id">
                    <div x-show="selectedGudangId == g.id" class="mt-2">
                        <p class="text-[11px] font-semibold" :class="g.sisa <= 0 ? 'text-rose-600' : 'text-purple-700'">
                            <template x-if="g.sisa > 0">
                                <span>Sisa kapasitas: <strong x-text="new Intl.NumberFormat('id-ID').format(g.sisa) + ' Kg'"></strong></span>
                            </template>
                            <template x-if="g.sisa <= 0">
                                <span>⚠️ Gudang Penuh! (Over: <strong x-text="new Intl.NumberFormat('id-ID').format(Math.abs(g.sisa)) + ' Kg'"></strong>)</span>
                            </template>
                        </p>
                    </div>
                </template>
            @else
                <div class="flex flex-col justify-between h-full">
                    <div>
                        <p class="text-purple-800 text-[11px] font-bold tracking-wider uppercase">Kapasitas Gudang</p>
                        <h3 class="text-xs font-semibold mt-2 text-slate-400">Belum ada gudang</h3>
                    </div>
                    <a href="{{ route('petani-gudang.create') }}" class="text-xs font-bold text-purple-700 hover:underline flex items-center gap-1">
                        <span>+</span> Tambah Gudang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Left Side: Batch Aktif -->
        <div class="col-span-12 xl:col-span-4 space-y-4">
            <h2 class="font-bold text-lg text-slate-800">Batch Aktif</h2>
            <div class="space-y-4">
                @forelse($activeBatches as $batch)
                    @php
                        $statusLabel = 'Di Gudang';
                        $statusBg = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        if ($batch->grade === 'C') {
                            $statusLabel = 'Busuk';
                            $statusBg = 'bg-rose-50 text-rose-700 border-rose-200';
                        } elseif ($batch->jumlah_stok < ($batch->panen->jumlah_kg ?? 0)) {
                            $statusLabel = 'Terjual Sebagian';
                            $statusBg = 'bg-amber-50 text-amber-800 border-amber-200';
                        }
                    @endphp
                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 relative space-y-3 hover:border-emerald-200 transition-all">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-lg border border-slate-200">
                                #BAT-{{ $batch->id }}
                            </span>
                            <span class="text-[10px] font-bold border rounded-full px-3 py-0.5 shadow-2xs {{ $statusBg }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ $batch->jenisKentang->nama_jenis ?? 'Kentang' }}</h4>
                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                <span class="text-xs text-slate-600 font-semibold">
                                    {{ number_format($batch->jumlah_stok, 0, ',', '.') }} Kg / Grade {{ $batch->grade }}
                                </span>
                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    🏢 {{ $batch->gudang->nama_gudang ?? 'Gudang' }}
                                </span>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    👤 Petani: {{ $batch->gudang->user->name ?? 'Belum Diketahui' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-[10px] font-medium text-slate-400 pt-2 border-t border-slate-100">
                            Panen: {{ \Carbon\Carbon::parse($batch->created_at)->translatedFormat('d M Y') }}
                        </p>
                    </div>
                @empty
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 text-center text-slate-400 text-sm">
                        Tidak ada batch aktif di gudang.
                    </div>
                @endforelse

                @include('partials.pagination', ['paginator' => $activeBatches, 'label' => 'batch aktif'])
            </div>
        </div>

        <!-- Right Side: Riwayat Panen -->
        <div class="col-span-12 xl:col-span-8 space-y-4">
            <h2 class="font-bold text-lg text-slate-800">Riwayat Panen</h2>

            <!-- Search & Filter Bar -->
            <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
                <form action="{{ route('panen.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
                    <div class="relative flex-1 min-w-[180px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" 
                            placeholder="Cari komoditas atau gudang...">
                    </div>

                    <div class="min-w-[140px]">
                        <select name="period" onchange="this.form.submit()" class="block w-full px-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                            <option value="">📅 Semua Periode</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-1.5 min-w-[260px]">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        <span class="text-xs text-slate-400 font-bold">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                            Filter
                        </button>
                        @if(request('search') || request('period') || request('start_date') || request('end_date'))
                            <a href="{{ route('panen.index') }}" class="inline-flex items-center px-3 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
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
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Komoditas & Gudang</th>
                            <th class="px-6 py-4">Berat (Kg)</th>
                            <th class="px-6 py-4">Pendapatan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($panens as $panen)
                            @php
                                $harga = $panen->jenisKentang->harga->harga ?? 20000;
                                $pendapatan = $panen->jumlah_kg * $harga;
                                
                                $statusPanen = 'Di Gudang';
                                $statusPanenBg = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                
                                if ($panen->grade === 'C') {
                                    $statusPanen = 'Busuk';
                                    $statusPanenBg = 'bg-rose-50 text-rose-700 border-rose-200';
                                } elseif (!$panen->stok || $panen->stok->jumlah_stok <= 0) {
                                    $statusPanen = 'Selesai';
                                    $statusPanenBg = 'bg-slate-100 text-slate-700 border-slate-200';
                                } elseif ($panen->stok->jumlah_stok < $panen->jumlah_kg) {
                                    $statusPanen = 'Terjual Sebagian';
                                    $statusPanenBg = 'bg-amber-50 text-amber-800 border-amber-200';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-600 font-medium">{{ optional($panen->tanggal_panen)->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $panen->jenisKentang->nama_jenis ?? '-' }}</div>
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="inline-flex items-center text-[10px] font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">
                                            🏢 {{ $panen->gudang->nama_gudang ?? '-' }}
                                        </span>
                                        <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            👤 Petani: {{ $panen->gudang->user->name ?? 'Belum Diketahui' }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-500">Grade {{ $panen->grade }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-700 font-mono text-sm font-bold">{{ number_format($panen->jumlah_kg, 0, ',', '.') }} Kg</div>
                                    @if($panen->jumlah_busuk_kg > 0 || $panen->jumlah_gagal_kg > 0)
                                        <div class="flex flex-col gap-1 mt-1">
                                            @if($panen->jumlah_busuk_kg > 0)
                                                <span class="text-[10px] text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-md inline-block">Busuk: {{ number_format($panen->jumlah_busuk_kg, 0, ',', '.') }} Kg</span>
                                            @endif
                                            @if($panen->jumlah_gagal_kg > 0)
                                                <span class="text-[10px] text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded-md inline-block">Gagal: {{ number_format($panen->jumlah_gagal_kg, 0, ',', '.') }} Kg</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-emerald-800 font-bold">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold border shadow-2xs {{ $statusPanenBg }}">
                                        {{ $statusPanen }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('panen.edit', $panen) }}" class="rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                                        <form action="{{ route('panen.destroy', $panen) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data panen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-xl bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    Belum ada data panen yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('partials.pagination', ['paginator' => $panens, 'label' => 'data panen'])
        </div>
    </div>
</div>
@endsection
