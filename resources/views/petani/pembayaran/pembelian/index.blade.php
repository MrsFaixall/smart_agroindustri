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
                <span>Pencairan & Tagihan Hasil Panen (Petani)</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Riwayat Pembayaran Petani</h1>
            <p class="text-slate-300 text-sm max-w-xl">Pantau penerimaan pembayaran dan bukti transfer atas penjualan komoditas panen ke Koperasi.</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="flex items-center gap-2.5 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-xs font-semibold text-emerald-800 shadow-2xs">
            <svg class="h-4 w-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error -->
    @if($errors->any())
        <div class="flex flex-col gap-1.5 rounded-2xl border border-rose-200 bg-rose-50/90 px-4 py-3 text-xs font-semibold text-rose-800 shadow-2xs">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- KPI STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-document-text class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Penjualan</p>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalTransaksi) }}</h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-check-circle class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-emerald-800 uppercase">Pembayaran Lunas</p>
                <h3 class="text-2xl font-extrabold text-emerald-900 tracking-tight">{{ number_format($totalLunas) }}</h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 border border-amber-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-clock class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-amber-800 uppercase">Menunggu Pembayaran</p>
                <h3 class="text-2xl font-extrabold text-amber-900 tracking-tight">{{ number_format($totalPending) }}</h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40 border border-purple-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-md shadow-purple-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-banknotes class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-purple-800 uppercase">Total Pendapatan</p>
                <h3 class="text-xl font-extrabold text-purple-900 tracking-tight">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="flex items-center gap-2 font-bold text-lg text-slate-800">Daftar Tagihan Penjualan Ke Koperasi</h2>
                <p class="text-xs text-slate-400">Rincian status pembayaran untuk setiap hasil panen yang disetorkan</p>
            </div>
        </div>
        <div class="overflow-x-auto">
    <x-petani-table-filter placeholder="Cari data riwayat pembayaran petani..." />

                <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Kode Transaksi</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Komoditas / Varietas</th>
                        <th class="px-6 py-4 text-right">Volume (Kg)</th>
                        <th class="px-6 py-4 text-right">Total Tagihan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Bukti / Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pembelians as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-700">TRX-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-slate-500 font-semibold">{{ \Carbon\Carbon::parse($p->tanggal_pembelian)->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $p->jenisKentang->nama_jenis ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">{{ number_format($p->jumlah_kg, 2, ',', '.') }} Kg</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-emerald-700">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($p->status === 'lunas')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-bold uppercase tracking-wide">✔ Lunas</span>
                                @else
                                    @php
                                        $pendingPayment = $p->pembayarans->where('status', 'pending')->first();
                                    @endphp
                                    @if($pendingPayment)
                                        <form action="{{ route('pembayaran-petani.accept', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Konfirmasi bahwa Anda telah menerima uang pembayaran dari Koperasi?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-bold text-[10px] shadow-sm hover:shadow transition-all flex items-center gap-1 uppercase tracking-wide">
                                                <span>✔</span> Terima Uang
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-lg text-xs font-bold uppercase tracking-wide">⏳ Belum Lunas</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('pembayaran.invoice', $p->id) }}" target="_blank" class="px-3 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold rounded-lg text-xs transition-colors">📄 Invoice</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">Belum ada riwayat tagihan penjualan panen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $pembelians->links() }}
        </div>
    </div>
</div>
@endsection
