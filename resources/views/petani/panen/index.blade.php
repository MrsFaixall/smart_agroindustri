@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ searchQuery: '' }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Panen</h1>
            <p class="text-slate-500 text-sm">Kelola pencatatan dan pantau status hasil panen Anda musim ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('panen.create') }}" class="rounded-xl bg-[#001842] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition flex items-center gap-2">
                <span>+</span> Catat Hasil Panen
            </a>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1: Total Musim Ini -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between h-36">
            <div>
                <p class="text-slate-400 text-[10px] font-bold tracking-wider uppercase">Total Musim Ini</p>
                <h3 class="text-3xl font-bold mt-2 text-slate-900">
                    {{ number_format(($totalMusimIni ?? 0) / 1000, 1, ',', '.') }} <span class="text-lg font-medium text-slate-400">Ton</span>
                </h3>
            </div>
            <p class="text-xs text-slate-400">{{ number_format($totalMusimIni ?? 0, 0, ',', '.') }} Kg tercatat</p>
        </div>

        <!-- Card 2: Harga Pasar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between h-36">
            <div>
                <p class="text-slate-400 text-[10px] font-bold tracking-wider uppercase">Harga Pasar</p>
                <h3 class="text-3xl font-bold mt-2 text-[#001842]">
                    Rp {{ number_format($hargaPasar ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <p class="text-xs text-slate-400">/kg rata-rata</p>
        </div>

        <!-- Card 3: Menunggu Bayar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between h-36">
            <div>
                <p class="text-slate-400 text-[10px] font-bold tracking-wider uppercase">Menunggu Bayar</p>
                <h3 class="text-3xl font-bold mt-2 text-slate-900">
                    @if(($menungguBayar ?? 0) >= 1000000)
                        Rp {{ number_format(($menungguBayar ?? 0) / 1000000, 1, ',', '.') }}Jt
                    @else
                        Rp {{ number_format($menungguBayar ?? 0, 0, ',', '.') }}
                    @endif
                </h3>
            </div>
            <p class="text-xs text-slate-400">Tagihan belum lunas</p>
        </div>

        <!-- Card 4: Kapasitas Gudang -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative flex flex-col justify-between h-36"
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
                    <div class="flex justify-between items-center">
                        <p class="text-slate-400 text-[10px] font-bold tracking-wider uppercase">Kapasitas Gudang</p>
                        @if($gudangs->count() > 1)
                            <select x-model="selectedGudangId" class="text-[11px] font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-1.5 py-0.5 focus:outline-none max-w-[130px] truncate">
                                <template x-for="g in gudangs" :key="g.id">
                                    <option :value="g.id" x-text="g.nama"></option>
                                </template>
                            </select>
                        @endif
                    </div>
                    
                    <template x-for="g in gudangs" :key="g.id">
                        <div x-show="selectedGudangId == g.id" class="mt-2 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-lg border border-amber-100 uppercase max-w-[140px] truncate" x-text="g.nama"></span>
                                <span class="text-xs font-bold" :class="g.persen >= 100 ? 'text-rose-600 font-black' : 'text-slate-700'" x-text="g.persen + '%'"></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full transition-all duration-300"
                                     :class="g.persen >= 100 ? 'bg-rose-500' : (g.persen >= 80 ? 'bg-amber-500' : 'bg-emerald-500')"
                                     :style="'width: ' + Math.min(100, g.persen) + '%'"></div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <template x-for="g in gudangs" :key="'sisa-' + g.id">
                    <div x-show="selectedGudangId == g.id">
                        <p class="text-[10px]" :class="g.sisa <= 0 ? 'text-rose-600 font-bold' : 'text-slate-400'">
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
                        <p class="text-slate-400 text-[10px] font-bold tracking-wider uppercase">Kapasitas Gudang</p>
                        <h3 class="text-xs font-semibold mt-2 text-slate-400">Belum ada gudang</h3>
                    </div>
                    <a href="{{ route('gudang.create') }}" class="text-[11px] font-bold text-blue-600 hover:underline flex items-center gap-1">
                        <span>+</span> Tambah Gudang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Left Side: Batch Aktif (Active Batches) -->
        <div class="col-span-12 xl:col-span-4 space-y-4">
            <h2 class="font-bold text-lg text-slate-800">Batch Aktif</h2>
            <div class="space-y-4">
                @forelse($activeBatches as $batch)
                    @php
                        $statusLabel = 'Di Gudang';
                        $statusBg = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                        if ($batch->grade === 'C') {
                            $statusLabel = 'Busuk';
                            $statusBg = 'bg-rose-50 text-rose-700 border-rose-100';
                        } elseif ($batch->jumlah_stok < ($batch->panen->jumlah_kg ?? 0)) {
                            $statusLabel = 'Terjual Sebagian';
                            $statusBg = 'bg-amber-50 text-amber-700 border-amber-100';
                        }
                    @endphp
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded border border-slate-200">
                                #BAT-{{ $batch->id }}
                            </span>
                            <span class="text-[10px] font-bold border rounded-full px-2.5 py-0.5 {{ $statusBg }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ $batch->jenisKentang->nama_jenis ?? 'Kentang' }}</h4>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="text-xs text-slate-500 font-medium">
                                    {{ number_format($batch->jumlah_stok, 0, ',', '.') }} Kg / Grade {{ $batch->grade }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    🏢 {{ $batch->gudang->nama_gudang ?? 'Gudang' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 pt-2 border-t border-slate-50">
                            {{ \Carbon\Carbon::parse($batch->created_at)->translatedFormat('d M Y') }}
                        </p>
                    </div>
                @empty
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 text-center text-slate-400 text-sm">
                        Tidak ada batch aktif di gudang.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Side: Riwayat Panen (Harvest History Table) -->
        <div class="col-span-12 xl:col-span-8 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-lg text-slate-800">Riwayat Panen</h2>
                <!-- Search input client side -->
                <div class="relative w-64">
                    <input type="text" x-model="searchQuery" placeholder="Cari data panen..." 
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-[#001842] transition-colors pl-8">
                    <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Komoditas & Gudang</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Berat (Kg)</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Pendapatan</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($panens as $panen)
                            @php
                                $harga = $panen->jenisKentang->harga->harga ?? 20000;
                                $pendapatan = $panen->jumlah_kg * $harga;
                                
                                // Deteksi status panen dari relasi stok
                                $statusPanen = 'Di Gudang';
                                $statusPanenBg = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                
                                if ($panen->grade === 'C') {
                                    $statusPanen = 'Busuk';
                                    $statusPanenBg = 'bg-rose-50 text-rose-700 border-rose-100';
                                } elseif (!$panen->stok || $panen->stok->jumlah_stok <= 0) {
                                    $statusPanen = 'Selesai';
                                    $statusPanenBg = 'bg-slate-100 text-slate-700 border-slate-200';
                                } elseif ($panen->stok->jumlah_stok < $panen->jumlah_kg) {
                                    $statusPanen = 'Terjual Sebagian';
                                    $statusPanenBg = 'bg-amber-50 text-amber-700 border-amber-100';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors"
                                x-show="searchQuery === '' || '{{ strtolower($panen->jenisKentang->nama_jenis ?? '') }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($panen->gudang->nama_gudang ?? '') }}'.includes(searchQuery.toLowerCase())">
                                <td class="px-6 py-4 text-slate-600 font-medium">{{ optional($panen->tanggal_panen)->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $panen->jenisKentang->nama_jenis ?? '-' }}</div>
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="inline-flex items-center text-[10px] font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">
                                            🏢 {{ $panen->gudang->nama_gudang ?? '-' }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-500">Grade {{ $panen->grade }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-sm font-semibold">{{ number_format($panen->jumlah_kg, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-700 font-semibold">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusPanenBg }}">
                                        {{ $statusPanen }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('panen.edit', $panen) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                                        <form action="{{ route('panen.destroy', $panen) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data panen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100 transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <p>Belum ada data panen.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
