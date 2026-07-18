@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Daftar Transaksi Pembayaran
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola seluruh transaksi pembayaran pembelian komoditas kentang.
            </p>
        </div>

        <a href="{{ route('pembayaran.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-[#001842] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Catat Pembayaran
        </a>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $payments->count() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Lunas</p>
            <h3 class="mt-2 text-2xl font-bold text-green-600">{{ $payments->where('status','lunas')->count() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Belum Lunas / Pending</p>
            <h3 class="mt-2 text-2xl font-bold text-amber-600">{{ $payments->whereIn('status',['belum lunas', 'pending'])->count() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-[#001842] uppercase tracking-wider">Total Nilai</p>
            <h3 class="mt-2 text-xl font-bold text-[#001842]">Rp {{ number_format($payments->sum('jumlah_bayar'),0,',','.') }}</h3>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
            <span class="bg-emerald-500 text-white rounded-full p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- CARD TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-b-4 border-b-[#001842] overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">ID Pesanan</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">Pelanggan</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">Metode Pembayaran</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">Jumlah Bayar</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal/Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($payments as $payment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-slate-500">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-900">
                        #{{ $payment->pembelian_id }}
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
                            <span class="text-slate-400 font-medium text-xs">Belum dipilih</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-emerald-600">
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
                        @elseif($payment->status === 'belum lunas')
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Belum Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Gagal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                        {{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('Y-m-d H:i:s') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        Belum ada transaksi pembayaran.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection