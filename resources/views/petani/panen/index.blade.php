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

                @include('partials.pagination', ['paginator' => $activeBatches, 'label' => 'batch aktif'])
            </div>
        </div>

        <!-- Right Side: Riwayat Panen (Harvest History Table) -->
        <div class="col-span-12 xl:col-span-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="font-bold text-lg text-slate-800">Riwayat Panen</h2>
            </div>

            <!-- Search & Filter Bar (Kalender & Periode) -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <form action="{{ route('panen.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
                    <!-- Search Text -->
                    <div class="relative flex-1 min-w-[180px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl text-xs bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors" 
                            placeholder="Cari kata kunci...">
                    </div>

                    <!-- Periode Select -->
                    <div class="min-w-[140px]">
                        <select name="period" onchange="this.form.submit()" class="block w-full px-3 py-2 border border-slate-300 rounded-xl text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors font-medium">
                            <option value="">📅 Semua Periode</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>

                    <!-- Kalender Rentang Tanggal -->
                    <div class="flex items-center gap-1.5 min-w-[260px]">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2 border border-slate-300 rounded-xl text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors">
                        <span class="text-xs text-slate-400 font-bold">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2 border border-slate-300 rounded-xl text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl text-white bg-[#001842] hover:bg-[#002a70] transition-colors shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 01-.707.293H10a1 1 0 01-.707-.293L2.879 7.293A1 1 0 012.586 6.586V4z"></path></svg>
                            Filter
                        </button>
                        @if(request('search') || request('period') || request('start_date') || request('end_date'))
                            <a href="{{ route('panen.index') }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
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

            @include('partials.pagination', ['paginator' => $panens, 'label' => 'data panen'])

            <!-- Activity Flow & Status Explanation Section -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6 mt-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                            <span>🔄</span> Alur Aktivitas & Penjelasan Status Panen
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Memahami perjalanan hasil panen Anda dari input <code class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-mono text-[11px]">Catat Hasil Panen</code> hingga selesai disalurkan.</p>
                    </div>
                </div>

                <!-- Step-by-Step Activity Flow -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 relative">
                    <!-- Step 1 -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 relative">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-[#001842] text-white font-bold text-xs flex items-center justify-center">1</span>
                            <h4 class="font-bold text-xs text-slate-800">1. Catat Panen</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Petani menginput data hasil panen via <span class="font-semibold text-slate-700">Form Tambah Panen</span>.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-emerald-50/60 p-4 rounded-2xl border border-emerald-100 relative">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                            <h4 class="font-bold text-xs text-emerald-900">2. Di Gudang</h4>
                        </div>
                        <p class="text-[11px] text-emerald-700 leading-relaxed">
                            Otomatis masuk gudang sebagai <span class="font-bold">Stok Aktif</span>. Status badge: <span class="font-bold text-emerald-800">Di Gudang</span>.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-100 relative">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-amber-600 text-white font-bold text-xs flex items-center justify-center">3</span>
                            <h4 class="font-bold text-xs text-amber-900">3. Transaksi Koperasi</h4>
                        </div>
                        <p class="text-[11px] text-amber-700 leading-relaxed">
                            Koperasi membeli stok. Status berubah jadi <span class="font-bold">Terjual Sebagian</span> jika stok tersisa.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-slate-100/80 p-4 rounded-2xl border border-slate-200 relative">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center">4</span>
                            <h4 class="font-bold text-xs text-slate-800">4. Selesai</h4>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Seluruh stok batch habis (0 Kg). Status panen berubah menjadi <span class="font-bold text-slate-800">Selesai</span>.
                        </p>
                    </div>
                </div>

                <!-- Legend status table -->
                <div class="bg-slate-50/60 p-4 rounded-2xl border border-slate-100 text-xs space-y-2">
                    <p class="font-bold text-slate-700 mb-1">📌 Beda Status "Di Gudang" vs "Selesai":</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-start gap-2 bg-white p-2.5 rounded-xl border border-slate-100 shadow-2xs">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 shrink-0 mt-0.5">Di Gudang</span>
                            <p class="text-slate-600 text-[11px]">Hasil panen baru dimasukkan dan stoknya <strong class="text-slate-800">masih utuh/tersedia di gudang</strong> untuk dijual atau diolah.</p>
                        </div>
                        <div class="flex items-start gap-2 bg-white p-2.5 rounded-xl border border-slate-100 shadow-2xs">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 shrink-0 mt-0.5">Selesai</span>
                            <p class="text-slate-600 text-[11px]">Seluruh stok dari batch panen ini <strong class="text-slate-800">sudah habis 0 Kg</strong> terdistribusi atau terjual ke pembeli/koperasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
