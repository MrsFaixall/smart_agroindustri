@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-emerald-900 via-teal-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Komersial & Penjualan Kentang Mitra</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Penjualan Kentang</h1>
            <p class="text-emerald-100/80 text-sm max-w-xl">Catatan transaksi penjualan stok kentang dari Gudang Mitra ke Retail / Konsumen.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('mitra.penjualan.create') }}" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-emerald-600/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Tambah Penjualan
            </a>
        </div>
    </div>

    <!-- Alert Info Alur Data -->
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-xs">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-emerald-800">Alur Data Penjualan Mitra</h3>
                <div class="mt-1 text-sm text-emerald-700">
                    <p>Halaman ini menampilkan transaksi penjualan kentang dari gudang Anda ke konsumen/pasar. Melakukan penjualan di sini akan <strong class="text-emerald-900">MEMOTONG STOK KENTANG</strong> di Gudang Mitra Anda secara real-time.</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 text-rose-700 font-semibold border border-rose-200">
        {{ session('error') }}
    </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <form action="{{ route('mitra.penjualan.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" 
                    placeholder="Cari varietas kentang atau nama pembeli...">
            </div>

            <div class="flex items-center gap-1.5 min-w-[260px]">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                <span class="text-xs text-slate-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-xs font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    Filter
                </button>
                @if(request('search') || request('start_date') || request('end_date'))
                    <a href="{{ route('mitra.penjualan.index') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
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
                    <th class="px-6 py-4">Komoditas / Varietas</th>
                    <th class="px-6 py-4">Pembeli (Konsumen / Retail)</th>
                    <th class="px-6 py-4 text-right">Volume</th>
                    <th class="px-6 py-4 text-right">Total Nilai</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
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
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">🛒 {{ $t->pembeli->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">{{ number_format($t->jumlah_kg, 2, ',', '.') }} Kg</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-emerald-700 text-right font-mono">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($t->status === 'lunas')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">Lunas</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">Belum Lunas</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('penjualan-buah.print-qr', $t->id) }}" target="_blank" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-all flex items-center gap-1 shadow-sm border border-indigo-100/50">
                                <span>🖨️</span> QR Tag
                            </a>
                            @if($t->status === 'belum lunas')
                                <form action="{{ route('mitra.penjualan.bayar', $t->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pelunasan transaksi ini?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">Lunasi</button>
                                </form>
                            @endif
                            <form action="{{ route('mitra.penjualan.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs transition-all">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada catatan transaksi penjualan kentang.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->isNotEmpty())
        @include('partials.pagination', ['paginator' => $transaksis, 'label' => 'transaksi penjualan'])
    @endif
</div>
@endsection
