@props([
    'title',
    'subtitle',
    'icon' => 'squares-2x2',
    'color' => 'emerald', // emerald, blue, amber, purple, indigo, rose, teal
    'actionUrl' => null,
    'actionText' => null,
    'actionIcon' => 'plus',
    'bgImage' => 'banner-petani-wide.png',
    'isEmptyBadge' => false
])

@php
    $gradients = [
        'emerald' => 'from-emerald-950/90 via-teal-950/85 to-slate-950/95',
        'blue'    => 'from-slate-950/95 via-blue-950/85 to-indigo-950/95',
        'amber'   => 'from-slate-950/95 via-amber-950/85 to-orange-950/95',
        'purple'  => 'from-slate-950/95 via-purple-950/85 to-fuchsia-950/95',
        'indigo'  => 'from-slate-950/95 via-indigo-950/85 to-purple-950/95',
        'rose'    => 'from-slate-950/95 via-rose-950/85 to-pink-950/95',
        'teal'    => 'from-slate-950/95 via-teal-950/85 to-emerald-950/95',
    ];
    
    $badgeColors = [
        'emerald' => 'bg-emerald-500/20 border-emerald-500/30 text-emerald-300',
        'blue'    => 'bg-blue-500/20 border-blue-500/30 text-blue-300',
        'amber'   => 'bg-amber-500/20 border-amber-500/30 text-amber-300',
        'purple'  => 'bg-purple-500/20 border-purple-500/30 text-purple-300',
        'indigo'  => 'bg-indigo-500/20 border-indigo-500/30 text-indigo-300',
        'rose'    => 'bg-rose-500/20 border-rose-500/30 text-rose-300',
        'teal'    => 'bg-teal-500/20 border-teal-500/30 text-teal-300',
    ];

    $dotColors = [
        'emerald' => 'bg-emerald-400',
        'blue'    => 'bg-blue-400',
        'amber'   => 'bg-amber-400',
        'purple'  => 'bg-purple-400',
        'indigo'  => 'bg-indigo-400',
        'rose'    => 'bg-rose-400',
        'teal'    => 'bg-teal-400',
    ];

    $btnGradients = [
        'emerald' => 'from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 shadow-emerald-500/30',
        'blue'    => 'from-blue-500 to-indigo-500 hover:from-blue-400 hover:to-indigo-400 shadow-blue-500/30',
        'amber'   => 'from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 shadow-amber-500/30',
        'purple'  => 'from-purple-500 to-fuchsia-500 hover:from-purple-400 hover:to-fuchsia-400 shadow-purple-500/30',
        'indigo'  => 'from-indigo-500 to-purple-500 hover:from-indigo-400 hover:to-purple-400 shadow-indigo-500/30',
        'rose'    => 'from-rose-500 to-pink-500 hover:from-rose-400 hover:to-pink-400 shadow-rose-500/30',
        'teal'    => 'from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 shadow-teal-500/30',
    ];

    $bgGradient = $gradients[$color] ?? $gradients['emerald'];
    $badgeColor = $badgeColors[$color] ?? $badgeColors['emerald'];
    $dotColor = $dotColors[$color] ?? $dotColors['emerald'];
    $btnGradient = $btnGradients[$color] ?? $btnGradients['emerald'];
@endphp

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-950 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden mb-6 min-h-[180px]">
    <!-- Dynamic HD Background Image -->
    @if($bgImage)
    <div class="absolute inset-0 z-0">
        <img src="{{ asset($bgImage) }}?v={{ time() }}" alt="Header Banner" class="w-full h-full object-cover object-center opacity-40">
        <div class="absolute inset-0 bg-gradient-to-r {{ $bgGradient }} pointer-events-none"></div>
    </div>
    @endif

    <!-- Decorative Glow Accents -->
    <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="relative z-10 space-y-1.5">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border {{ $badgeColor }} text-xs font-semibold mb-1 backdrop-blur-md">
            <span class="w-2 h-2 rounded-full {{ $dotColor }} animate-pulse"></span>
            <span class="uppercase tracking-wider">Modul Petani Smart Agro</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight flex items-center gap-3">
            @switch($color)
                @case('blue')
                    <x-heroicon-o-building-storefront class="w-8 h-8 opacity-90 text-blue-400" />
                    @break
                @case('amber')
                    <x-heroicon-o-currency-dollar class="w-8 h-8 opacity-90 text-amber-400" />
                    @break
                @case('indigo')
                    <x-heroicon-o-banknotes class="w-8 h-8 opacity-90 text-indigo-400" />
                    @break
                @case('purple')
                    <x-heroicon-o-briefcase class="w-8 h-8 opacity-90 text-purple-400" />
                    @break
                @case('rose')
                    <x-heroicon-o-document-chart-bar class="w-8 h-8 opacity-90 text-rose-400" />
                    @break
                @case('teal')
                    <x-heroicon-o-beaker class="w-8 h-8 opacity-90 text-teal-400" />
                    @break
                @default
                    <x-heroicon-o-archive-box class="w-8 h-8 opacity-90 text-emerald-400" />
            @endswitch
            <span class="flex items-center gap-2">
                {{ $title }}
            </span>
        </h1>
        <p class="text-slate-200 text-sm max-w-xl font-medium leading-relaxed">{{ $subtitle }}</p>
    </div>
    
    @if($actionUrl && $actionText)
    <div class="relative z-10">
        <a href="{{ $actionUrl }}" class="bg-gradient-to-r {{ $btnGradient }} text-white px-5 py-3 rounded-2xl flex items-center gap-2.5 transition-all duration-200 text-sm font-bold shadow-lg transform hover:-translate-y-0.5 border border-white/20">
            <span class="text-xl leading-none font-extrabold">+</span>
            <span>{{ $actionText }}</span>
        </a>
    </div>
    @endif
</div>
