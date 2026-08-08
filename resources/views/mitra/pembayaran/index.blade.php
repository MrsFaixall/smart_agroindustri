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
                <span>Keuangan & Transaksi Logistik Mitra</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen Pembayaran</h1>
            <p class="text-slate-300 text-sm max-w-xl">Kelola seluruh bukti pembayaran dan pelunasan transaksi pembelian dari Koperasi.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('mitra.pembayaran.create') }}" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-emerald-600/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Catat Pembayaran Baru
            </a>
        </div>
    </div>

    <!-- KPI STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
                📄
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Transaksi</p>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalTransaksi) }}</h3>
            </div>
        </div>

        <div class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
                ✓
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-emerald-800 uppercase">Lunas</p>
                <h3 class="text-2xl font-extrabold text-emerald-900 tracking-tight">{{ number_format($totalLunas) }}</h3>
            </div>
        </div>

        <div class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white">
                ⏳
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-amber-800 uppercase">Belum Lunas</p>
                <h3 class="text-2xl font-extrabold text-amber-900 tracking-tight">{{ number_format($totalPending) }}</h3>
            </div>
        </div>

        <div class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-600 text-white">
                💰
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-emerald-800 uppercase">Total Tagihan</p>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <!-- TABEL 1: DAFTAR INVOICE TAGIHAN -->
    <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/60">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                Daftar Tagihan Pembelian (Mitra -> Koperasi)
            </h2>
            <p class="text-xs text-slate-400">Seluruh data transaksi pembelian yang perlu dilunasi atau sudah lunas.</p>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Koperasi Penjual</th>
                        <th class="px-6 py-4">Komoditas / Varietas</th>
                        <th class="px-6 py-4 text-right">Volume</th>
                        <th class="px-6 py-4 text-right">Total Nilai</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksis as $t)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-700">🏢 {{ $t->koperasi->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-bold text-slate-800">{{ $t->jenisKentang->nama_jenis ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-extrabold text-slate-800">{{ number_format($t->jumlah_kg, 2, ',', '.') }} Kg</td>
                        <td class="px-6 py-4 text-right font-mono font-extrabold text-emerald-700">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($t->status === 'lunas')
                                <span class="inline-flex px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase">Lunas</span>
                            @else
                                <span class="inline-flex px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase animate-pulse">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($t->status !== 'lunas')
                                <a href="{{ route('mitra.pembayaran.create', ['penjualan_id' => $t->id]) }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                                    Bayar Sekarang
                                </a>
                            @else
                                <span class="text-xs text-slate-400 font-bold">Terbayar ✓</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada tagihan pembelian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksis->isNotEmpty())
            @include('partials.pagination', ['paginator' => $transaksis, 'label' => 'tagihan', 'pageName' => 'penjualan_page'])
        @endif
    </div>

    <!-- TABEL 2: RIWAYAT BUKTI BAYAR -->
    <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/60">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                Log & Bukti Riwayat Pembayaran (Mitra -> Koperasi)
            </h2>
            <p class="text-xs text-slate-400">Bukti pembayaran lunas dan slip transaksi resmi.</p>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">ID Transaksi</th>
                        <th class="px-6 py-4">Tanggal Pembayaran</th>
                        <th class="px-6 py-4">Komoditas / Koperasi</th>
                        <th class="px-6 py-4">Metode Transfer</th>
                        <th class="px-6 py-4 text-right">Jumlah Dibayar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono font-bold text-slate-600">PAY-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 text-sm">{{ $p->penjualanBuah->jenisKentang->nama_jenis ?? '-' }}</span><br>
                            <span class="text-[10px] text-slate-400 font-semibold">🏢 {{ $p->penjualanBuah->koperasi->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-600 text-xs">
                            💳 {{ $p->metodePembayaran->nama_metode ?? 'Manual Transfer' }}
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-extrabold text-emerald-700">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase">Sukses</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('mitra.pembayaran.invoice', $p->id) }}" target="_blank" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-all border border-indigo-100/50">
                                    Invoice
                                </a>
                                <a href="{{ route('mitra.pembayaran.struk', $p->id) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all border border-slate-200/80">
                                    Struk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada riwayat bukti pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->isNotEmpty())
            @include('partials.pagination', ['paginator' => $payments, 'label' => 'pembayaran', 'pageName' => 'payment_page'])
        @endif
    </div>

</div>
@endsection
