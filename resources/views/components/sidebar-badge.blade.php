@props(['count' => 0, 'pulse' => false])

@if($count > 0)
<span class="ml-auto w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center shadow-sm shadow-rose-500/40 {{ $pulse ? 'animate-pulse' : '' }}" title="Memerlukan Perhatian">
    {{ $count > 99 ? '99+' : $count }}
</span>
@endif
