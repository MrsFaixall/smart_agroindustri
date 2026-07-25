@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-3xl p-8 shadow-xl shadow-slate-100/60 border border-slate-100 text-center space-y-6">
        
        @if(in_array($transactionStatus, ['capture', 'settlement', 'success', 'lunas', 'berhasil']) || request('transaction_status') == 'settlement' || request('status_code') == '200')
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pembayaran Berhasil!</h1>
            <p class="text-slate-500 text-sm">Terima kasih, pembayaran Anda untuk Order ID <strong class="text-slate-800 font-mono">{{ $orderId }}</strong> telah berhasil diproses.</p>

            @if(isset($pembayaran) && $pembayaran)
            <div class="flex items-center justify-center gap-3 pt-2">
                <a href="{{ route('pembayaran.invoice', $pembayaran->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition-all">
                    📄 Lihat Invoice Resmi
                </a>
                <a href="{{ route('pembayaran.struk', $pembayaran->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold hover:bg-emerald-100 transition-all">
                    🖨️ Cetak Struk (80mm)
                </a>
            </div>
            @endif
        @elseif(in_array($transactionStatus, ['pending']))
            <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-3xl flex items-center justify-center mx-auto shadow-lg shadow-amber-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Menunggu Pembayaran</h1>
            <p class="text-slate-500 text-sm">Silakan selesaikan pembayaran Anda untuk Order ID <strong class="text-slate-800 font-mono">{{ $orderId }}</strong> sesuai dengan instruksi yang diberikan.</p>
        @else
            <div class="w-20 h-20 bg-rose-100 text-rose-600 rounded-3xl flex items-center justify-center mx-auto shadow-lg shadow-rose-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pembayaran Gagal</h1>
            <p class="text-slate-500 text-sm">Mohon maaf, transaksi Anda dengan Order ID <strong class="text-slate-800 font-mono">{{ $orderId }}</strong> gagal atau dibatalkan.</p>
        @endif

        <div class="pt-6 border-t border-slate-100">
            <a href="{{ route('pembayaran.index') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-slate-900 to-indigo-950 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 hover:from-slate-800 hover:to-indigo-900 transition-all">
                Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
</div>
@endsection
