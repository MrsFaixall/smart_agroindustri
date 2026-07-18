@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center space-y-6">
        
        @if(in_array($transactionStatus, ['capture', 'settlement', 'success']))
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-slate-900">Pembayaran Berhasil!</h1>
            <p class="text-slate-500">Terima kasih, pembayaran Anda untuk Order ID <strong>{{ $orderId }}</strong> telah berhasil diproses.</p>
        @elseif(in_array($transactionStatus, ['pending']))
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-slate-900">Menunggu Pembayaran</h1>
            <p class="text-slate-500">Silakan selesaikan pembayaran Anda untuk Order ID <strong>{{ $orderId }}</strong> sesuai dengan instruksi yang diberikan.</p>
        @else
            <div class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-slate-900">Pembayaran Gagal</h1>
            <p class="text-slate-500">Mohon maaf, transaksi Anda dengan Order ID <strong>{{ $orderId }}</strong> gagal atau dibatalkan.</p>
        @endif

        <div class="pt-6 border-t border-slate-100">
            <a href="{{ route('pembayaran.index') }}" class="inline-flex items-center justify-center rounded-xl bg-[#001842] px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
                Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
</div>
@endsection
