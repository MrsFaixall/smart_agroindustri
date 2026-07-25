@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>Arsip & Laporan Logistik</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Daftar Transaksi & Riwayat Pembayaran</h1>
            <p class="text-slate-300 text-sm max-w-xl">Lihat seluruh daftar riwayat transaksi, cetak invoice resmi, dan struk pembayaran.</p>
        </div>
    </div>

    <!-- KPI STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-document-text class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Transaksi</p>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalTransaksi) }}</h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-check-circle class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-emerald-800 uppercase">Lunas</p>
                <h3 class="text-2xl font-extrabold text-emerald-900 tracking-tight">{{ number_format($totalLunas) }}</h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 border border-amber-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-clock class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-amber-800 uppercase">Belum Lunas / Pending</p>
                <h3 class="text-2xl font-extrabold text-amber-900 tracking-tight">{{ number_format($totalPending) }}</h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40 border border-purple-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-md shadow-purple-500/20 group-hover:scale-110 transition-transform">
                <x-heroicon-o-banknotes class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-purple-800 uppercase">Total Nilai</p>
                <h3 class="text-xl font-extrabold text-purple-900 tracking-tight">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <form action="{{ route('daftar-transaksi.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" 
                    placeholder="Cari kode TRX/INV atau nama petani...">
            </div>

            <div class="min-w-[150px]">
                <select name="period" onchange="this.form.submit()" class="block w-full px-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold">
                    <option value="">📅 Semua Periode</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            <div class="flex items-center gap-1.5 min-w-[280px]">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                <span class="text-xs text-slate-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20">
                    Filter
                </button>
                @if(request('search') || request('period') || request('start_date') || request('end_date'))
                    <a href="{{ route('daftar-transaksi.index') }}" class="inline-flex items-center px-3 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- CARD TABLE -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">No. Invoice (INV)</th>
                        <th class="px-6 py-4">Pelanggan / Petani</th>
                        <th class="px-6 py-4">Metode Pembayaran</th>
                        <th class="px-6 py-4">Jumlah Bayar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Tanggal / Waktu</th>
                        <th class="px-6 py-4 text-center">Aksi / Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-mono text-xs">
                            {{ $payments->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            <span class="block font-mono text-indigo-950 font-extrabold text-sm">{{ $payment->kode_inv }}</span>
                            <span class="block text-[11px] font-semibold text-slate-400 font-mono tracking-tight mt-0.5">Ref: {{ $payment->pembelian->kode_trx ?? ('TRX-' . $payment->pembelian_id) }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $payment->pembelian->petani->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->snap_token && !$payment->metode_pembayaran_id)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    Midtrans {{ $payment->payment_type ? '- ' . strtoupper(str_replace('_', ' ', $payment->payment_type)) : '' }}
                                </span>
                            @elseif($payment->metodePembayaran)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $payment->metodePembayaran->kategori ?? 'Bank' }} - {{ $payment->metodePembayaran->bank }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium text-xs">Belum dipilih</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-extrabold text-emerald-700 font-mono">
                            Rp {{ number_format($payment->jumlah_bayar,0,',','.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($payment->status === 'lunas' || $payment->status === 'berhasil')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 shadow-2xs">
                                    ✓ Lunas
                                </span>
                            @elseif($payment->status === 'pending')
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-bold text-amber-800 shadow-2xs">
                                    ⏳ Menunggu
                                </span>
                            @elseif($payment->status === 'belum lunas')
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-bold text-amber-800 shadow-2xs">
                                    ⏳ Belum Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-200 px-3 py-1 text-[11px] font-bold text-rose-700 shadow-2xs">
                                    ✕ Gagal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                            {{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('pembayaran.invoice', $payment->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition-colors">
                                    Invoice
                                </a>
                                <a href="{{ route('pembayaran.struk', $payment->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold hover:bg-emerald-100 transition-colors">
                                    Struk
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Section -->
    @if($payments->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 font-medium">
                Menampilkan <span class="font-bold text-slate-800">{{ $payments->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $payments->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $payments->total() }}</span> transaksi
            </p>
            <div class="pagination-custom">
                {{ $payments->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
