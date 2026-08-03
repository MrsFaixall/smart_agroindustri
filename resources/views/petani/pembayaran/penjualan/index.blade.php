@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header VIP -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-[#001842] via-slate-900 to-[#001842] p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Keuangan & Transaksi</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Tagihan Penjualan Panen</h1>
            <p class="text-slate-300 text-sm max-w-xl">Lihat status pembayaran penjualan panen dari Koperasi.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200 shadow-sm flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Transaksi -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Transaksi</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $totalTransaksi }}</h3>
            </div>
        </div>

        <!-- Lunas -->
        <div class="bg-white p-5 rounded-3xl border border-emerald-100 shadow-sm flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-50 rounded-full opacity-50"></div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-0.5">Lunas</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $totalLunas }}</h3>
            </div>
        </div>

        <!-- Belum Lunas -->
        <div class="bg-white p-5 rounded-3xl border border-amber-100 shadow-sm flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-amber-50 rounded-full opacity-50"></div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-0.5">Belum Lunas / Pending</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $totalPending }}</h3>
            </div>
        </div>

        <!-- Total Nilai -->
        <div class="bg-white p-5 rounded-3xl border border-purple-100 shadow-sm flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-purple-50 rounded-full opacity-50"></div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider mb-0.5">Total Nilai</p>
                <h3 class="text-xl font-extrabold text-purple-700 font-mono">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div>
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Pembelian Panen (Tagihan dari Petani)</h2>
                <p class="text-xs text-slate-500 font-medium">Seluruh data transaksi tagihan yang perlu diproses atau telah dilunasi.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                    <th class="px-6 py-4">No. Transaksi (TRX)</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Pihak Terkait & Komoditas</th>
                    <th class="px-6 py-4 text-right">Volume (Kg)</th>
                    <th class="px-6 py-4 text-right">Total Tagihan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi / Opsi Pembayaran</th>
                </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        
                @forelse($transaksis as $t)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">TRX-{{ Carbon\Carbon::parse($t->tanggal_transaksi)->format('dmy') }}-{{ str_pad($t->id, 3, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">Ref: TRX-{{ str_pad($t->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $t->koperasi->name ?? '-' }} <span class="text-slate-400 font-normal">/</span> {{ $t->pembeli->name ?? '-' }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $t->jenisKentang->nama_jenis ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">{{ number_format($t->jumlah_kg, 2, ',', '.') }} Kg</td>
                    <td class="px-6 py-4 text-sm font-extrabold text-emerald-600 text-right font-mono">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($t->status === 'lunas')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">✔ Lunas</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">⏳ Belum Lunas</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if($t->status !== 'lunas')
                                <form action="{{ route('penjualan-buah.bayar', $t->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm hover:shadow-emerald-500/30">
                                        Bayar / Lunas
                                    </button>
                                </form>
                            @else
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl">Sudah Lunas</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data tagihan penjualan panen.</td>
                </tr>
                @endforelse

                    </tbody>
                </table>
            </div>
            
            @if($transaksis->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                { $transaksis->links() }
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
