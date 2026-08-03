@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Compact Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gradient-to-r from-slate-900 via-purple-950 to-indigo-950 p-5 rounded-2xl text-white shadow-md relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-0.5">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/20 border border-purple-500/30 text-purple-300 text-[11px] font-semibold backdrop-blur-md">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                <span>Pengadaan & Transaksi Pengepul Koperasi</span>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight">Daftar Transaksi Pembelian</h1>
            <p class="text-slate-300 text-xs">Pencatatan transaksi pengadaan kentang oleh Pengepul (Koperasi) dari Petani & Konsumen.</p>
        </div>
        <div class="relative z-10 flex-shrink-0">
            <a href="{{ route('pembelian.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white px-4 py-2 rounded-xl flex items-center gap-1.5 text-xs font-bold shadow-md shadow-purple-600/30 transition-all">
                <span>+ Catat Pembelian Baru</span>
            </a>
        </div>
    </div>

    <!-- Compact Keterangan Info Banner -->
    <div class="flex items-center gap-2.5 rounded-xl border border-purple-200/80 bg-purple-50/70 px-4 py-2.5 text-xs font-medium text-purple-950 shadow-2xs">
        <span class="text-base flex-shrink-0">💡</span>
        <div class="text-[11px] leading-tight">
            <strong class="text-purple-900 font-bold">Alur Transaksi:</strong> <span>Pengepul (Koperasi) membeli komoditas kentang dari Petani maupun Konsumen. Konsumen juga dapat membeli bibit/jual kentang ke Koperasi.</span>
        </div>
    </div>

    <!-- Compact KPI Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-xs flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 font-bold">
                <x-heroicon-o-shopping-bag class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Transaksi</p>
                <h3 class="text-lg font-extrabold text-slate-800 leading-tight">{{ number_format($totalTransaksi) }} <span class="text-xs font-semibold text-slate-400">TRX</span></h3>
            </div>
        </div>

        <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-xs flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 font-bold">
                <x-heroicon-o-archive-box class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Volume</p>
                <h3 class="text-lg font-extrabold text-amber-900 leading-tight">{{ number_format($totalJumlah, 2, ',', '.') }} <span class="text-xs font-semibold text-amber-700">Kg</span></h3>
            </div>
        </div>

        <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-xs flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 font-bold">
                <x-heroicon-o-banknotes class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Nilai Pembelian</p>
                <h3 class="text-lg font-extrabold text-emerald-900 leading-tight">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-xs font-semibold text-emerald-800 shadow-2xs">
            <x-heroicon-o-check-circle class="h-4 w-4 text-emerald-600 flex-shrink-0" /> {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error -->
    @if($errors->any())
        <div class="flex flex-col gap-1.5 rounded-xl border border-rose-200 bg-rose-50/90 px-4 py-3 text-xs font-semibold text-rose-800 shadow-2xs">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Compact Search & Filter Bar -->
    <div class="bg-white p-3 rounded-2xl shadow-xs border border-slate-100">
        <form action="{{ route('pembelian.index') }}" method="GET" class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full text-xs">
            <div class="relative flex-1 min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-8 pr-2.5 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500" 
                    placeholder="Cari TRX, pengepul, atau penjual...">
            </div>

            <div class="w-36">
                <select name="period" onchange="this.form.submit()" class="block w-full px-2.5 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50/50 text-slate-700 font-semibold focus:outline-none focus:ring-1 focus:ring-purple-500">
                    <option value="">📅 Semua Periode</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            <div class="flex items-center gap-1">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-2 py-1.5 border border-slate-200 rounded-xl text-[11px] bg-slate-50/50 text-slate-700">
                <span class="text-[10px] text-slate-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-2 py-1.5 border border-slate-200 rounded-xl text-[11px] bg-slate-50/50 text-slate-700">
            </div>

            <div class="flex items-center gap-1.5">
                <button type="submit" class="px-3.5 py-2 font-bold rounded-xl text-white bg-purple-600 hover:bg-purple-700 transition-all text-xs">
                    Filter
                </button>
                @if(request('search') || request('period') || request('start_date') || request('end_date'))
                    <a href="{{ route('pembelian.index') }}" class="px-2.5 py-2 font-semibold rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 text-xs">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Compact Table Card (No Horizontal Oversize) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-3.5 py-2.5">TRX & Tanggal</th>
                        <th class="px-3.5 py-2.5">Pembeli (Pengepul)</th>
                        <th class="px-3.5 py-2.5">Penjual (Petani/Konsumen)</th>
                        <th class="px-3.5 py-2.5">Komoditas</th>
                        <th class="px-3.5 py-2.5 text-right">Volume</th>
                        <th class="px-3.5 py-2.5 text-right">Total Harga</th>
                        <th class="px-3.5 py-2.5 text-center">Status</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembelians as $pembelian)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Kode TRX & Tanggal -->
                            <td class="px-3.5 py-2.5">
                                <span class="font-mono font-extrabold text-purple-950 text-xs block leading-tight">{{ $pembelian->kode_trx }}</span>
                                <span class="text-[10px] text-slate-400 font-mono block">
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->format('d/m/Y') }}
                                </span>
                            </td>

                            <!-- Pembeli (Koperasi / Pengepul) -->
                            <td class="px-3.5 py-2.5">
                                <div class="font-bold text-slate-800 text-xs leading-tight">{{ $pembelian->koperasi->name ?? 'Koperasi' }}</div>
                                <span class="text-[9px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200/50 inline-block">Pengepul</span>
                            </td>

                            <!-- Penjual (Petani / Konsumen) -->
                            <td class="px-3.5 py-2.5">
                                <div class="font-bold text-slate-800 text-xs leading-tight">{{ $pembelian->petani->name ?? '-' }}</div>
                                @php $rolePenjual = $pembelian->petani->role ?? 'petani'; @endphp
                                @if($rolePenjual === 'konsumen')
                                    <span class="text-[9px] font-semibold text-teal-700 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-200/50 inline-block">Konsumen</span>
                                @else
                                    <span class="text-[9px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200/50 inline-block">Petani</span>
                                @endif
                            </td>

                            <!-- Komoditas -->
                            <td class="px-3.5 py-2.5 font-semibold text-slate-700 text-xs">
                                {{ $pembelian->jenisKentang->nama_jenis ?? '-' }}
                            </td>

                            <!-- Volume -->
                            <td class="px-3.5 py-2.5 text-right font-mono font-bold text-slate-700 text-xs">
                                {{ number_format($pembelian->jumlah_kg, 0, ',', '.') }} Kg
                            </td>

                            <!-- Total Harga -->
                            <td class="px-3.5 py-2.5 text-right font-mono font-extrabold text-emerald-700 text-xs">
                                Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                            </td>

                            <!-- Status -->
                            <td class="px-3.5 py-2.5 text-center">
                                @if($pembelian->status === 'lunas')
                                    <span class="inline-block rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                                        ✓ Lunas
                                    </span>
                                @else
                                    <span class="inline-block rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-800">
                                        ⏳ Belum
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-3.5 py-2.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    @if($pembelian->status !== 'lunas')
                                        <a href="{{ route('pembayaran.create', ['pembelian_id' => $pembelian->id]) }}" class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-800 text-[11px] font-bold hover:bg-emerald-100">
                                            Bayar
                                        </a>
                                    @endif
                                    <a href="{{ route('pembelian.edit', $pembelian->id) }}" class="px-2 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold hover:bg-blue-100">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 font-medium">
                                Belum ada transaksi pembelian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($pembelians->hasPages())
        <div class="mt-4 flex items-center justify-between bg-white p-3 rounded-2xl border border-slate-100 text-xs">
            <p class="text-slate-500 font-medium">
                Menampilkan {{ $pembelians->firstItem() ?? 0 }} - {{ $pembelians->lastItem() ?? 0 }} dari {{ $pembelians->total() }} transaksi
            </p>
            <div>
                {{ $pembelians->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
