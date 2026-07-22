@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Manajemen & Riwayat Pembayaran
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola seluruh tagihan pembelian komoditas kentang dan bukti riwayat pembayaran.
            </p>
        </div>

        <a href="{{ route('pembayaran.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-[#001842] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Catat Pembayaran Baru
        </a>
    </div>

    {{-- KPI STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalTransaksi) }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Lunas</p>
            <h3 class="mt-2 text-2xl font-bold text-green-600">{{ number_format($totalLunas) }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Belum Lunas / Pending</p>
            <h3 class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($totalPending) }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-[#001842] uppercase tracking-wider">Total Nilai</p>
            <h3 class="mt-2 text-xl font-bold text-[#001842]">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR (KALENDER & PERIODE) --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <form action="{{ route('pembayaran.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
            <!-- Search Text -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors" 
                    placeholder="Cari kata kunci...">
            </div>

            <!-- Periode Select -->
            <div class="min-w-[150px]">
                <select name="period" onchange="this.form.submit()" class="block w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors font-medium">
                    <option value="">📅 Semua Periode</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            <!-- Kalender Rentang Tanggal -->
            <div class="flex items-center gap-1.5 min-w-[280px]">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2 border border-slate-300 rounded-lg text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors">
                <span class="text-xs text-slate-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2 border border-slate-300 rounded-lg text-xs bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg text-white bg-[#001842] hover:bg-[#002a70] transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
                @if(request('search') || request('period') || request('start_date') || request('end_date'))
                    <a href="{{ route('pembayaran.index') }}" class="inline-flex items-center px-3 py-2 border border-slate-300 text-xs font-semibold rounded-lg text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ========================================== --}}
    {{-- TABEL 1: DAFTAR PEMBELIAN & STATUS BAYAR   --}}
    {{-- ========================================== --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#001842]"></span>
                    Daftar Pembelian Komoditas (Tagihan)
                </h2>
                <p class="text-xs text-slate-500">Seluruh data transaksi pembelian yang perlu diproses atau telah dilunasi.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-b-4 border-b-[#001842] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">No. Transaksi (TRX)</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Petani & Komoditas</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right">Volume (Kg)</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right">Total Tagihan</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">Status</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">Aksi / Opsi Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pembelians as $pembelian)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-blue-950 font-mono">
                                    <span class="block font-mono text-blue-950 font-extrabold text-sm">{{ $pembelian->kode_trx }}</span>
                                    <span class="block text-[11px] font-semibold text-slate-400 font-mono tracking-tight mt-0.5">Ref: TRX-{{ str_pad($pembelian->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $pembelian->petani->name ?? '-' }}</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">{{ $pembelian->jenisKentang->nama_jenis ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-slate-700 font-mono">
                                    {{ number_format($pembelian->jumlah_kg, 2, ',', '.') }} Kg
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600 font-mono">
                                    Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pembelian->status === 'lunas')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pembelian->status === 'lunas')
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('pembayaran.invoice', $pembelian->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-[#001842] text-xs font-bold hover:bg-blue-100 transition-colors">
                                                Invoice
                                            </a>
                                            <a href="{{ route('pembayaran.struk', $pembelian->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold hover:bg-emerald-100 transition-colors">
                                                Struk
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ route('pembayaran.create', ['pembelian_id' => $pembelian->id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#001842] text-white text-xs font-bold hover:bg-[#002a70] transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada transaksi pembelian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pembelians->hasPages())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="font-bold text-slate-800">{{ $pembelians->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $pembelians->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $pembelians->total() }}</span> pembelian
                </p>
                <div>
                    {{ $pembelians->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- TABEL 2: RIWAYAT BUKTI PEMBAYARAN          --}}
    {{-- ========================================== --}}
    <div class="space-y-3 pt-4 border-t border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    Riwayat Bukti Pembayaran (Invoice & Struk)
                </h2>
                <p class="text-xs text-slate-500">Seluruh bukti transaksi pembayaran yang telah dibuat dan diproses.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-b-4 border-b-emerald-600 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">No. Invoice (INV)</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Pelanggan / Petani</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Metode Pembayaran</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right">Jumlah Dibayar</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">Status</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal/Waktu</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">Cetak Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <span class="block font-mono text-blue-950 font-extrabold text-sm">{{ $payment->kode_inv }}</span>
                                    <span class="block text-[11px] font-semibold text-slate-400 font-mono tracking-tight mt-0.5">Ref: {{ $payment->pembelian->kode_trx ?? ('TRX-' . $payment->pembelian_id) }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ $payment->pembelian->petani->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($payment->snap_token && !$payment->metode_pembayaran_id)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                            Midtrans {{ $payment->payment_type ? '- ' . strtoupper(str_replace('_', ' ', $payment->payment_type)) : '' }}
                                        </span>
                                    @elseif($payment->metodePembayaran)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $payment->metodePembayaran->kategori ?? 'Bank' }} - {{ $payment->metodePembayaran->bank }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-medium text-xs">Tunai / Direct</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-emerald-600 font-mono text-right">
                                    Rp {{ number_format($payment->jumlah_bayar,0,',','.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($payment->status === 'lunas' || $payment->status === 'berhasil')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            Lunas
                                        </span>
                                    @elseif($payment->status === 'pending')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                                    {{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('pembayaran.invoice', $payment->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-[#001842] text-xs font-bold hover:bg-blue-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Invoice
                                        </a>
                                        <a href="{{ route('pembayaran.struk', $payment->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold hover:bg-emerald-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            Struk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada bukti pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="font-bold text-slate-800">{{ $payments->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $payments->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $payments->total() }}</span> pembayaran
                </p>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection