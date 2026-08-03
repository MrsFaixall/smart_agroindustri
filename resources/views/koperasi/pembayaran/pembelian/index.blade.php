@extends('layouts.app')

@push('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Midtrans Snap JS -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransClientKey ?? '' }}"></script>
<style>
    .payment-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .payment-card:hover {
        transform: translateY(-2px);
    }
    .payment-card.active-payment-card {
        border-color: #10b981 !important; /* emerald-500 */
        background-color: #f0fdf4 !important; /* emerald-50 */
        box-shadow: 0 4px 12px -1px rgba(16, 185, 129, 0.12), 0 2px 4px -1px rgba(16, 185, 129, 0.08) !important;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.25s ease-out forwards;
    }
</style>
@endpush

@section('content')
<div class="space-y-8">

    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Keuangan & Transaksi Logistik</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen & Riwayat Pembayaran</h1>
            <p class="text-slate-300 text-sm max-w-xl">Kelola seluruh tagihan pembelian komoditas dan bukti riwayat pembayaran.</p>
        </div>
        <div class="relative z-10">
            <button type="button" onclick="openPaymentModal(null, '', 0, '')" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-emerald-600/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Catat Pembayaran Baru
            </button>
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
        <form action="{{ route('pembayaran.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" 
                    placeholder="Cari kode TRX/INV atau nama petani...">
            </div>

            <div class="min-w-[150px]">
                <select name="period" onchange="this.form.submit()" class="block w-full px-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                    <option value="">📅 Semua Periode</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            <div class="flex items-center gap-1.5 min-w-[280px]">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-2.5 py-2 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                <span class="text-xs text-slate-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-2.5 py-2 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    Filter
                </button>
                @if(request('search') || request('period') || request('start_date') || request('end_date'))
                    <a href="{{ route('pembayaran.index') }}" class="inline-flex items-center px-3 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL 1: DAFTAR PEMBELIAN & STATUS BAYAR -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    Daftar Pembelian Komoditas (Tagihan)
                </h2>
                <p class="text-xs text-slate-400">Seluruh data transaksi pembelian yang perlu diproses atau telah dilunasi.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">No. Transaksi (TRX)</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Petani & Komoditas</th>
                            <th class="px-6 py-4 text-right">Volume (Kg)</th>
                            <th class="px-6 py-4 text-right">Total Tagihan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi / Opsi Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pembelians as $pembelian)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 font-mono">
                                    <span class="block font-mono text-indigo-950 font-extrabold text-sm">{{ $pembelian->kode_trx }}</span>
                                    <span class="block text-[11px] font-semibold text-slate-400 font-mono tracking-tight mt-0.5">Ref: TRX-{{ str_pad($pembelian->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium text-xs">
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800">{{ $pembelian->petani->name ?? '-' }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">{{ $pembelian->jenisKentang->nama_jenis ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-700 font-mono">
                                    {{ number_format($pembelian->jumlah_kg, 2, ',', '.') }} Kg
                                </td>
                                <td class="px-6 py-4 text-right font-extrabold text-emerald-700 font-mono">
                                    Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pembelian->status === 'lunas')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 shadow-2xs">
                                            ✓ Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-bold text-amber-800 shadow-2xs">
                                            ⏳ Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pembelian->status === 'lunas')
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('pembayaran.invoice', $pembelian->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition-colors">
                                                Invoice
                                            </a>
                                            <a href="{{ route('pembayaran.struk', $pembelian->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold hover:bg-emerald-100 transition-colors">
                                                Struk
                                            </a>
                                        </div>
                                    @else
                                        <button type="button" onclick="openPaymentModal({{ $pembelian->id }}, '{{ $pembelian->petani->name ?? 'N/A' }}', {{ $pembelian->total_harga }}, '{{ $pembelian->petani_id ?? 0 }}')" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition-all">
                                            Bayar Sekarang
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    Belum ada transaksi pembelian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pembelians->hasPages())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="font-bold text-slate-800">{{ $pembelians->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $pembelians->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $pembelians->total() }}</span> pembelian
                </p>
                <div>
                    {{ $pembelians->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- TABEL 2: RIWAYAT BUKTI PEMBAYARAN -->
    <div class="space-y-4 pt-4 border-t border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    Riwayat Bukti Pembayaran (Invoice & Struk)
                </h2>
                <p class="text-xs text-slate-400">Seluruh bukti transaksi pembayaran yang telah dibuat dan diproses.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">No. Invoice (INV)</th>
                            <th class="px-6 py-4">Pelanggan / Petani</th>
                            <th class="px-6 py-4">Metode Pembayaran</th>
                            <th class="px-6 py-4 text-right">Jumlah Dibayar</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Tanggal / Waktu</th>
                            <th class="px-6 py-4 text-center">Cetak Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-400 font-mono text-xs">
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    <span class="block font-mono text-blue-950 font-extrabold text-sm">{{ $payment->kode_inv }}</span>
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
                                        <span class="text-slate-400 font-medium text-xs">Tunai / Direct</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-extrabold text-emerald-700 font-mono text-right">
                                    Rp {{ number_format($payment->jumlah_bayar,0,',','.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($payment->status === 'lunas' || $payment->status === 'berhasil')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 shadow-2xs">
                                            ✓ Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-bold text-amber-800 shadow-2xs">
                                            ⏳ Pending
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
                                    Belum ada bukti pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="font-bold text-slate-800">{{ $payments->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $payments->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $payments->total() }}</span> pembayaran
                </p>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        @endif
    </div>


    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden animate-fadeIn my-8">
                <!-- Top Accent Line -->
                <div class="h-2 w-full bg-gradient-to-r from-emerald-600 to-teal-600"></div>
                
                <!-- Close Button -->
                <button type="button" onclick="closePaymentModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-2 hover:bg-slate-50 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <!-- Header -->
                <div class="p-6 pb-2">
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Catat Pembayaran Transaksi</h3>
                    <p class="text-xs text-slate-400 mt-1">Pilih tagihan transaksi pembelian dan selesaikan rincian pembayaran.</p>
                </div>

                <!-- Form -->
                <form action="{{ route('pembayaran.store') }}" method="POST" id="payment-form" class="p-6 pt-2 space-y-6 max-h-[75vh] overflow-y-auto">
                    @csrf

                    <!-- Tagihan Summary -->
                    <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 rounded-2xl p-5 flex items-center justify-between border border-emerald-100/80 shadow-sm">
                        <div>
                            <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-0.5">Pelanggan / Petani</p>
                            <p id="summary-petani" class="font-extrabold text-slate-800 text-sm">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-0.5">Total Tagihan</p>
                            <p id="summary-total" class="text-xl font-extrabold text-emerald-700 font-mono">Rp 0</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Pembelian Select -->
                        <div class="space-y-2">
                            <label for="pembelian_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Transaksi Pembelian</label>
                            <select name="pembelian_id" id="pembelian_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs select2" required>
                                <option value="" disabled selected>-- Pilih Transaksi Pembelian (Tagihan Belum Lunas) --</option>
                                @forelse($unpaidPembelians as $pembelian)
                                    <option value="{{ $pembelian->id }}" data-petani="{{ $pembelian->petani->name ?? 'N/A' }}" data-petani-id="{{ $pembelian->petani_id }}" data-total="{{ $pembelian->total_harga }}">
                                        TRX-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }} — {{ $pembelian->petani->name ?? 'Petani' }} — Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                                    </option>
                                @empty
                                    <option value="" disabled>-- Tidak ada tagihan transaksi yang belum lunas --</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Metode Pembayaran Grid -->
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Metode Pembayaran</label>
                            
                            <input type="hidden" name="metode_pembayaran_id" id="metode_pembayaran_id" value="">
                            <input type="hidden" name="midtrans_payment_type" id="midtrans_payment_type" value="">

                            <div class="space-y-3">
                                <!-- Kategori Manual -->
                                <div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <!-- Tunai Card -->
                                        <button type="button" data-method-type="tunai" class="payment-card flex items-center gap-3 p-3.5 rounded-xl border-2 border-slate-100 hover:border-emerald-200 bg-white hover:bg-slate-50 transition-all text-left group">
                                            <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:scale-105 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-extrabold text-slate-800">Tunai / Kas Koperasi</p>
                                                <p class="text-[10px] text-slate-400 font-semibold">Bayar langsung dengan kas</p>
                                            </div>
                                        </button>

                                        <!-- Transfer Manual Card -->
                                        <button type="button" data-method-type="manual" class="payment-card flex items-center gap-3 p-3.5 rounded-xl border-2 border-slate-100 hover:border-emerald-200 bg-white hover:bg-slate-50 transition-all text-left group">
                                            <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600 group-hover:scale-105 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-extrabold text-slate-800">Transfer Bank Manual</p>
                                                <p class="text-[10px] text-slate-400 font-semibold">Transfer ke rekening petani</p>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Dropdown Rekening Manual -->
                                <div id="manual-account-select-wrapper" class="hidden space-y-1.5 p-3 bg-slate-50 rounded-xl border border-slate-100 animate-fadeIn">
                                    <label for="manual_bank_select" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pilih Rekening Tujuan</label>
                                    <select id="manual_bank_select" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs bg-white text-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-semibold" disabled>
                                        <option value="" disabled selected>-- Pilih Transaksi Terlebih Dahulu --</option>
                                    </select>
                                </div>

                                <!-- Accordion Midtrans -->
                                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden shadow-sm">
                                    <!-- Accordion Header -->
                                    <button type="button" id="midtrans-accordion-btn" class="w-full flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-200 focus:outline-none">
                                        <div class="flex items-center gap-3">
                                            <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-left">
                                                <span id="accordion-title" class="text-xs font-extrabold text-slate-800">Lihat Semua Metode Midtrans</span>
                                                <p id="accordion-subtitle" class="text-[10px] text-slate-400 font-semibold">Virtual Account, QRIS, GoPay, ShopeePay</p>
                                            </div>
                                        </div>
                                        <div class="text-slate-400 transition-transform duration-200" id="accordion-chevron" style="transition: transform 0.2s;">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Accordion Content -->
                                    <div id="midtrans-accordion-content" class="hidden transition-all duration-300">
                                        <div class="p-3.5 space-y-3 bg-white">
                                            <!-- QRIS & Dompet Digital -->
                                            <div class="space-y-1.5">
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">QRIS & DOMPET DIGITAL</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                    <button type="button" data-method-type="midtrans" data-channel="qris" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">QRIS</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                    <button type="button" data-method-type="midtrans" data-channel="gopay" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">GoPay</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                    <button type="button" data-method-type="midtrans" data-channel="shopeepay" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">ShopeePay</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- VA / Bank Transfer -->
                                            <div class="space-y-1.5">
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">VIRTUAL ACCOUNT / TRANSFER BANK</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    <button type="button" data-method-type="midtrans" data-channel="bca_va" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">BCA VA</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                    <button type="button" data-method-type="midtrans" data-channel="bri_va" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">BRI VA</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                    <button type="button" data-method-type="midtrans" data-channel="mandiri_bill" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">Mandiri VA</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                    <button type="button" data-method-type="midtrans" data-channel="bni_va" class="payment-card flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                        <span class="text-xs font-bold text-slate-700">BNI VA</span>
                                                        <div class="active-indicator hidden text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nominal Bayar -->
                            <div class="space-y-1.5">
                                <label for="jumlah_bayar" class="block text-xs font-bold text-slate-500">Nominal Dibayar (Rp)</label>
                                <input type="number" step="0.01" name="jumlah_bayar" id="jumlah_bayar" value="{{ old('jumlah_bayar') }}" placeholder="0" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 font-mono font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
                            </div>

                            <!-- Tanggal Pembayaran -->
                            <div class="space-y-1.5">
                                <label for="tanggal_pembayaran" class="block text-xs font-bold text-slate-500">Tanggal Pembayaran</label>
                                <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
                            </div>
                        </div>

                        <!-- Status Pembayaran -->
                        <div class="space-y-1.5">
                            <label for="status" class="block text-xs font-bold text-slate-500">Status Pelunasan</label>
                            <select name="status" id="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-emerald-500 transition-all outline-none font-bold" required>
                                <option value="lunas" selected>✅ Lunas (Selesai)</option>
                                <option value="pending">⏳ Pending (Tertunda)</option>
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div class="space-y-1.5">
                            <label for="catatan" class="block text-xs font-bold text-slate-500">Catatan / Keterangan (Opsional)</label>
                            <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan tambahan..." class="w-full rounded-xl border border-slate-200 p-3 text-xs text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closePaymentModal()" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</button>
                        <button type="submit" id="pay-button" class="rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 px-5 py-2 text-xs font-bold text-white shadow-lg shadow-emerald-600/30 transition-all">Simpan & Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Modal Logic -->
    <script>
        window.metodePerPetani = {!! isset($metodePerPetaniJson) ? $metodePerPetaniJson : '{}' !!};
        window.notifyRoute = "{{ route('koperasi.pembayaran.notify-petani') }}";

        function openPaymentModal(pembelianId = null, petaniName = '', totalHarga = 0, petaniId = '') {
            const modal = document.getElementById('paymentModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            const select = $('#pembelian_id');
            if (select.length) {
                if (pembelianId) {
                    select.val(pembelianId).trigger('change');
                } else {
                    select.val('').trigger('change');
                }
            }
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const pembelianSelect = document.getElementById('pembelian_id');
            const summaryPetani = document.getElementById('summary-petani');
            const summaryTotal = document.getElementById('summary-total');
            const jumlahBayar = document.getElementById('jumlah_bayar');
            const paymentForm = document.getElementById('payment-form');
            const hiddenMethodInput = document.getElementById('metode_pembayaran_id');
            const hiddenMidtransTypeInput = document.getElementById('midtrans_payment_type');
            const manualAccountWrapper = document.getElementById('manual-account-select-wrapper');
            const manualBankSelect = document.getElementById('manual_bank_select');
            const cards = document.querySelectorAll('.payment-card');

            const accordionBtn = document.getElementById('midtrans-accordion-btn');
            const accordionContent = document.getElementById('midtrans-accordion-content');
            const accordionChevron = document.getElementById('accordion-chevron');
            const accordionTitle = document.getElementById('accordion-title');
            const accordionSubtitle = document.getElementById('accordion-subtitle');

            function updateSummary() {
                if (!pembelianSelect) return;
                const selectedOption = pembelianSelect.options[pembelianSelect.selectedIndex];
                
                if (manualBankSelect) {
                    manualBankSelect.innerHTML = '';
                }

                if (selectedOption && selectedOption.value) {
                    const petani = selectedOption.getAttribute('data-petani');
                    const petaniId = selectedOption.getAttribute('data-petani-id');
                    const total = parseFloat(selectedOption.getAttribute('data-total')) || 0;
                    summaryPetani.textContent = petani;
                    summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    jumlahBayar.value = total;

                    if (manualBankSelect && petaniId) {
                        const metodes = window.metodePerPetani[petaniId];
                        if (!metodes || metodes.length === 0) {
                            manualBankSelect.disabled = true;
                            const opt = document.createElement('option');
                            opt.value = "";
                            opt.disabled = true;
                            opt.selected = true;
                            opt.textContent = "Petani belum mendaftarkan Rekening Bank";
                            manualBankSelect.appendChild(opt);
                            
                            fetch(window.notifyRoute, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ petani_id: petaniId })
                            }).then(res => res.json()).then(data => {
                                if (data.success) {
                                    console.log("Notification sent:", data.message);
                                }
                            }).catch(err => console.error(err));
                        } else {
                            manualBankSelect.disabled = false;
                            const opt = document.createElement('option');
                            opt.value = "";
                            opt.disabled = true;
                            opt.selected = true;
                            opt.textContent = "-- Pilih Rekening Transfer Manual --";
                            manualBankSelect.appendChild(opt);
                            
                            metodes.forEach(m => {
                                const option = document.createElement('option');
                                option.value = m.id;
                                option.textContent = `🏢 ${m.kategori} - ${m.bank} (A/N ${m.atas_nama} - ${m.no_rekening})`;
                                manualBankSelect.appendChild(option);
                            });
                        }
                    }
                } else {
                    summaryPetani.textContent = '-';
                    summaryTotal.textContent = 'Rp 0';
                    jumlahBayar.value = '';
                    if (manualBankSelect) {
                        manualBankSelect.disabled = true;
                        const opt = document.createElement('option');
                        opt.value = "";
                        opt.disabled = true;
                        opt.selected = true;
                        opt.textContent = "-- Pilih Transaksi Terlebih Dahulu --";
                        manualBankSelect.appendChild(opt);
                    }
                }
            }

            if (pembelianSelect) {
                pembelianSelect.addEventListener('change', updateSummary);
            }

            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2').select2({ width: '100%' });
                $('#pembelian_id').on('change', updateSummary);
            }

            if (accordionBtn && accordionContent) {
                accordionBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isHidden = accordionContent.classList.contains('hidden');
                    if (isHidden) {
                        accordionContent.classList.remove('hidden');
                        if (accordionChevron) accordionChevron.style.transform = 'rotate(180deg)';
                    } else {
                        accordionContent.classList.add('hidden');
                        if (accordionChevron) accordionChevron.style.transform = 'rotate(0deg)';
                    }
                });
            }

            function selectCard(card) {
                cards.forEach(c => {
                    c.classList.remove('active-payment-card');
                    const indicator = c.querySelector('.active-indicator');
                    if (indicator) indicator.classList.add('hidden');
                });
                
                card.classList.add('active-payment-card');
                const indicator = card.querySelector('.active-indicator');
                if (indicator) indicator.classList.remove('hidden');

                const methodType = card.getAttribute('data-method-type');
                
                if (methodType === 'tunai') {
                    hiddenMethodInput.value = '';
                    hiddenMidtransTypeInput.value = '';
                    if (manualAccountWrapper) manualAccountWrapper.classList.add('hidden');
                    if (manualBankSelect) manualBankSelect.removeAttribute('required');
                    
                    if (accordionTitle) accordionTitle.textContent = 'Lihat Semua Metode Midtrans';
                    if (accordionSubtitle) accordionSubtitle.textContent = 'Virtual Account, QRIS, GoPay, ShopeePay';
                    if (accordionContent) accordionContent.classList.add('hidden');
                    if (accordionChevron) accordionChevron.style.transform = 'rotate(0deg)';
                } else if (methodType === 'manual') {
                    if (manualBankSelect) {
                        hiddenMethodInput.value = manualBankSelect.value;
                        manualBankSelect.setAttribute('required', 'required');
                    }
                    hiddenMidtransTypeInput.value = '';
                    if (manualAccountWrapper) manualAccountWrapper.classList.remove('hidden');
                    
                    if (accordionTitle) accordionTitle.textContent = 'Lihat Semua Metode Midtrans';
                    if (accordionSubtitle) accordionSubtitle.textContent = 'Virtual Account, QRIS, GoPay, ShopeePay';
                    if (accordionContent) accordionContent.classList.add('hidden');
                    if (accordionChevron) accordionChevron.style.transform = 'rotate(0deg)';
                } else if (methodType === 'midtrans') {
                    const channel = card.getAttribute('data-channel');
                    hiddenMethodInput.value = 'midtrans';
                    hiddenMidtransTypeInput.value = channel;
                    if (manualAccountWrapper) manualAccountWrapper.classList.add('hidden');
                    if (manualBankSelect) manualBankSelect.removeAttribute('required');

                    const channelName = card.querySelector('span').textContent;
                    if (accordionTitle) accordionTitle.textContent = 'Midtrans: ' + channelName;
                    if (accordionSubtitle) accordionSubtitle.textContent = 'Metode Instant Terpilih (Klik untuk mengubah)';
                }
            }

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    selectCard(this);
                });
            });

            if (manualBankSelect) {
                manualBankSelect.addEventListener('change', function() {
                    const activeCard = document.querySelector('.payment-card.active-payment-card');
                    if (activeCard && activeCard.getAttribute('data-method-type') === 'manual') {
                        hiddenMethodInput.value = this.value;
                    }
                });
            }

            const defaultCard = document.querySelector('.payment-card[data-method-type="tunai"]');
            if (defaultCard) selectCard(defaultCard);

            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    if (hiddenMethodInput && hiddenMethodInput.value === 'midtrans') {
                        e.preventDefault();

                        const pembelianId = pembelianSelect.value;
                        const amount = jumlahBayar.value;
                        const catatan = document.getElementById('catatan').value;
                        const paymentType = hiddenMidtransTypeInput.value;

                        if (!pembelianId || !amount) {
                            alert('Harap pilih transaksi pembelian dan jumlah bayar yang valid.');
                            return;
                        }

                        const payButton = document.getElementById('pay-button');
                        const originalText = payButton.textContent;
                        payButton.disabled = true;
                        payButton.textContent = 'Menghubungkan ke Midtrans...';

                        fetch('{{ route("midtrans.snap-token") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                pembelian_id: pembelianId,
                                jumlah_bayar: amount,
                                catatan: catatan,
                                payment_type: paymentType
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            payButton.disabled = false;
                            payButton.textContent = originalText;

                            if (typeof snap !== 'undefined') {
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        window.location.href = '{{ route("pembayaran.finish") }}?order_id=' + data.order_id + '&status_code=200&transaction_status=settlement';
                                    },
                                    onPending: function(result) {
                                        window.location.href = '{{ route("pembayaran.finish") }}?order_id=' + data.order_id + '&status_code=201&transaction_status=pending';
                                    },
                                    onError: function(result) {
                                        window.location.href = '{{ route("pembayaran.finish") }}?order_id=' + data.order_id + '&status_code=407&transaction_status=error';
                                    },
                                    onClose: function() {
                                        alert('Pembayaran dibatalkan.');
                                    }
                                });
                            } else {
                                alert('Midtrans Snap SDK gagal dimuat. Harap periksa koneksi internet Anda.');
                            }
                        })
                        .catch(error => {
                            payButton.disabled = false;
                            payButton.textContent = originalText;
                            console.error('Error:', error);
                            alert('Gagal mendapatkan token pembayaran Midtrans: ' + (error.error || 'Terjadi kesalahan internal.'));
                        });
                    }
                });
            }
        });
    </script>

</div>
@endsection