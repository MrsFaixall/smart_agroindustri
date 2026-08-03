@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pusat Notifikasi</h2>
                <p class="text-slate-500 mt-2 font-medium">Tugas dan informasi terbaru untuk Anda</p>
            </div>
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>

        <div class="space-y-5">
            @if(isset($systemAlerts) && $systemAlerts->count() > 0)
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 mt-8">Tugas Wajib</h3>
                @foreach($systemAlerts as $notif)
                @php
                    $id = $notif->id;
                    $style = match(true) {
                        str_contains($id, 'gudang') => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-500', 'iconBg' => 'bg-indigo-100', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'btn' => 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-200'],
                        str_contains($id, 'harga') => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-500', 'iconBg' => 'bg-emerald-100', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0V8', 'btn' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-200'],
                        str_contains($id, 'pembayaran') || str_contains($id, 'bayar') => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-500', 'iconBg' => 'bg-cyan-100', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'btn' => 'bg-cyan-600 hover:bg-cyan-700 text-white shadow-cyan-200'],
                        str_contains($id, 'profil') => ['bg' => 'bg-fuchsia-50', 'text' => 'text-fuchsia-700', 'border' => 'border-fuchsia-500', 'iconBg' => 'bg-fuchsia-100', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'btn' => 'bg-fuchsia-600 hover:bg-fuchsia-700 text-white shadow-fuchsia-200'],
                        str_contains($id, 'benih') || str_contains($id, 'penanaman') => ['bg' => 'bg-lime-50', 'text' => 'text-lime-700', 'border' => 'border-lime-500', 'iconBg' => 'bg-lime-100', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'btn' => 'bg-lime-600 hover:bg-lime-700 text-white shadow-lime-200'],
                        str_contains($id, 'distribusi') => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-500', 'iconBg' => 'bg-sky-100', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z', 'icon_extra' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />', 'btn' => 'bg-sky-600 hover:bg-sky-700 text-white shadow-sky-200'],
                        str_contains($id, 'panen') || str_contains($id, 'stok') => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-500', 'iconBg' => 'bg-amber-100', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'btn' => 'bg-amber-600 hover:bg-amber-700 text-white shadow-amber-200'],
                        str_contains($id, 'pembelian') || str_contains($id, 'penjualan') || str_contains($id, 'penawaran') => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-500', 'iconBg' => 'bg-violet-100', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'btn' => 'bg-violet-600 hover:bg-violet-700 text-white shadow-violet-200'],
                        default => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-500', 'iconBg' => 'bg-rose-100', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'btn' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-rose-200'],
                    };
                @endphp
                <div class="group flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 rounded-2xl transition-all {{ $style['bg'] }} border-l-4 {{ $style['border'] }} shadow-sm hover:shadow-md">
                    <div class="flex-shrink-0 mt-1 sm:mt-0">
                        <div class="w-12 h-12 rounded-xl {{ $style['iconBg'] }} {{ $style['text'] }} flex items-center justify-center animate-pulse ring-4 ring-white/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $style['icon'] }}"></path>
                                {!! $style['icon_extra'] ?? '' !!}
                            </svg>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <p class="{{ $style['text'] }} font-bold text-[15px] leading-relaxed">
                            {{ $notif->pesan }}
                        </p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-1 rounded-md bg-white/60 {{ $style['text'] }} uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text', 'bg', $style['text']) }}"></span>
                                Tindakan Wajib
                            </span>
                        </div>
                    </div>

                    <div class="flex-shrink-0 w-full sm:w-auto mt-4 sm:mt-0">
                        <a href="{{ $notif->url }}" class="flex items-center justify-center gap-2 w-full px-5 py-3 {{ $style['btn'] }} rounded-xl text-sm font-bold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Selesaikan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            @endif

            @if(isset($notifikasis) && $notifikasis->count() > 0)
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 mt-8">Pemberitahuan</h3>
                @foreach($notifikasis as $notif)
                <div class="group flex items-start gap-4 p-5 rounded-2xl transition-all {{ $notif->is_read ? 'bg-slate-50 border border-slate-100 hover:bg-slate-100' : 'bg-white border-l-4 border-blue-500 shadow-md ring-1 ring-slate-100' }}">
                    <div class="flex-shrink-0 mt-1">
                        @if($notif->tipe_notifikasi === 'stok_menipis')
                            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        @elseif($notif->tipe_notifikasi === 'request_panen')
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-xl {{ $notif->is_read ? 'bg-slate-100 text-slate-400' : 'bg-blue-50 text-blue-500' }} flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <p class="{{ $notif->is_read ? 'text-slate-500' : 'text-slate-800 font-bold' }} text-[15px] leading-relaxed">
                            {{ $notif->pesan }}
                        </p>
                        <p class="text-xs text-slate-400 mt-2 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if(!$notif->is_read)
                    <div class="flex-shrink-0">
                        <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors tooltip" title="Tandai Dibaca">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            @endif
            
            @if((!isset($systemAlerts) || $systemAlerts->count() === 0) && (!isset($notifikasis) || $notifikasis->count() === 0))
            <div class="text-center py-20 bg-slate-50/50 rounded-3xl border border-slate-100 border-dashed">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Semua Selesai!</h3>
                <p class="text-slate-500 font-medium">Anda tidak memiliki tugas atau pemberitahuan baru saat ini.</p>
            </div>
            @endif
        </div>

        @if(isset($notifikasis) && $notifikasis->hasPages())
        <div class="mt-8 pt-6 border-t border-slate-100">
            {{ $notifikasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
