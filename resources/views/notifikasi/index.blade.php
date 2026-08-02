@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <h2 class="text-2xl font-extrabold text-slate-800 mb-6">Pusat Notifikasi</h2>

        <div class="space-y-4">
            @if(isset($systemAlerts) && $systemAlerts->count() > 0)
                @foreach($systemAlerts as $notif)
                <div class="flex items-start gap-4 p-4 rounded-2xl transition-all bg-amber-50 border-l-4 border-amber-500 shadow-sm">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center animate-pulse">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <p class="text-amber-900 font-semibold text-sm leading-relaxed">
                            {{ $notif->pesan }}
                        </p>
                        <p class="text-xs text-amber-600/70 mt-2 font-medium">Tindakan Wajib Diperlukan</p>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ $notif->url }}" class="inline-block px-4 py-2 bg-amber-500 hover:bg-amber-600 border border-amber-600 rounded-xl text-xs font-bold text-white transition-colors shadow-sm">
                            Selesaikan Sekarang
                        </a>
                    </div>
                </div>
                @endforeach
            @endif

            @forelse($notifikasis as $notif)
            <div class="flex items-start gap-4 p-4 rounded-2xl transition-all {{ $notif->is_read ? 'bg-slate-50 border border-slate-100' : 'bg-blue-50 border-l-4 border-blue-500 shadow-sm' }}">
                <div class="flex-shrink-0 mt-1">
                    @if($notif->tipe_notifikasi === 'stok_menipis')
                        <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    @elseif($notif->tipe_notifikasi === 'request_panen')
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <p class="{{ $notif->is_read ? 'text-slate-600' : 'text-slate-800 font-semibold' }} text-sm leading-relaxed">
                        {{ $notif->pesan }}
                    </p>
                    <p class="text-xs text-slate-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                @if(!$notif->is_read)
                <div class="flex-shrink-0">
                    <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 transition-colors shadow-sm">
                            Tandai Dibaca
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            @if(!isset($systemAlerts) || $systemAlerts->count() === 0)
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <p class="text-slate-500 font-medium">Belum ada notifikasi.</p>
            </div>
            @endif
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifikasis->links() }}
        </div>
    </div>
</div>
@endsection
