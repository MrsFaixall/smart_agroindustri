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
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between h-36">
            <div>
                <p class="text-slate-400 text-[10px] font-bold tracking-wider uppercase">Kapasitas Gudang</p>
                @if($primaryGudang)
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-lg border border-amber-100 uppercase max-w-[140px] truncate">
                            {{ $primaryGudang->nama_gudang }}
                        </span>
                        <span class="text-xs font-bold text-slate-700">{{ $primaryGudang->persentase_kapasitas }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $primaryGudang->persentase_kapasitas }}%"></div>
                    </div>
                @else
                    <h3 class="text-sm font-semibold mt-2 text-slate-400">Belum ada gudang</h3>
                @endif
            </div>
            @if($primaryGudang)
                <p class="text-[10px] text-slate-400">
                    Sisa kapasitas: {{ number_format($primaryGudang->kapasitas_max - $primaryGudang->kapasitas_terpakai, 0, ',', '.') }} Kg
                </p>
            @else
                <p class="text-xs text-slate-400">-</p>
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
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ number_format($batch->jumlah_stok, 0, ',', '.') }} Kg / Grade {{ $batch->grade }}
                            </p>
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
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Komoditas</th>
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
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $panen->gudang->nama_gudang ?? '-' }} (Grade {{ $panen->grade }})</div>
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
