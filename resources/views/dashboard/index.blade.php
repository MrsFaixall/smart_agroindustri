@extends('layouts.app')

@section('content')
<div class="space-y-8">
    @php
        $role = strtolower(auth()->user()->role ?? 'admin');
        // fallback mapping just in case
        if (!in_array($role, ['admin', 'petani', 'koperasi'])) {
            $role = 'admin';
        }
        $bannerImage = 'banner-' . $role . '-wide.png';
        
        $dashboardTitle = match($role) {
            'petani' => 'Dashboard Petani',
            'koperasi' => 'Dashboard Pengepul',
            default => 'Dashboard Utama',
        };
    @endphp
    <!-- Header Bagian Atas dengan Banner Image -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900 p-6 md:p-10 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden min-h-[320px]">
        <!-- Dynamic Background Image based on Role -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset($bannerImage) }}?v={{ time() }}" alt="Dashboard Banner" class="w-full h-full object-cover object-center opacity-100">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/40 to-transparent pointer-events-none"></div>
        </div>

        <!-- Decorative Glow Elements -->
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Role Session: <span class="uppercase font-bold">{{ $role }}</span></span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ $dashboardTitle }}</h2>
            <p class="text-slate-300 text-sm max-w-xl">Selamat datang kembali, <span class="font-bold text-white">{{ auth()->user()->name ?? 'Pengguna' }}</span>! Berikut ringkasan statistik operasional hari ini.</p>
        </div>
        <div class="relative z-10 flex flex-wrap gap-3">
            <button class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/15 px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-semibold hover:shadow-lg">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-emerald-400"/> Ekspor Data
            </button>
            <a href="{{ route('panen.create') }}" class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-emerald-500/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Input Panen
            </a>
        </div>
    </div>

    <!-- Statistik Utama (Colorful & Modern Cards) -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-5">
        @php
            $stats = [
                [
                    'label' => 'TOTAL GUDANG',
                    'value' => number_format($totalGudang ?? 0),
                    'sub' => '↗ +2 Gudang baru',
                    'icon' => 'home',
                    'gradient' => 'from-blue-600 to-indigo-600',
                    'bg_card' => 'bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40',
                    'border' => 'border-blue-100',
                    'badge_bg' => 'bg-blue-100 text-blue-700 border-blue-200/60',
                    'glow' => 'bg-blue-500/10'
                ],
                [
                    'label' => 'TOTAL PETANI',
                    'value' => number_format($totalPetani ?? 0),
                    'sub' => '↗ +12 bulan ini',
                    'icon' => 'user-group',
                    'gradient' => 'from-emerald-500 to-teal-600',
                    'bg_card' => 'bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40',
                    'border' => 'border-emerald-100',
                    'badge_bg' => 'bg-emerald-100 text-emerald-700 border-emerald-200/60',
                    'glow' => 'bg-emerald-500/10'
                ],
                [
                    'label' => 'TOTAL KOPERASI',
                    'value' => number_format($totalKoperasi ?? 0),
                    'sub' => '— Stabil',
                    'icon' => 'truck',
                    'gradient' => 'from-purple-600 to-indigo-600',
                    'bg_card' => 'bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40',
                    'border' => 'border-purple-100',
                    'badge_bg' => 'bg-purple-100 text-purple-700 border-purple-200/60',
                    'glow' => 'bg-purple-500/10'
                ],
                [
                    'label' => 'TOTAL PANEN',
                    'value' => number_format(($totalPanenKg ?? 0) / 1000, 2, ',', '.').' Ton',
                    'sub' => number_format($totalPanenKg ?? 0, 0, ',', '.').' Kg tercatat',
                    'icon' => 'archive-box',
                    'gradient' => 'from-amber-500 to-orange-600',
                    'bg_card' => 'bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40',
                    'border' => 'border-amber-100',
                    'badge_bg' => 'bg-amber-100 text-amber-800 border-amber-200/60',
                    'glow' => 'bg-amber-500/10'
                ],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="{{ $s['bg_card'] }} {{ $s['border'] }} p-5 rounded-3xl border shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="{{ $s['glow'] }} absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-slate-500 text-[11px] font-bold tracking-wider uppercase">{{ $s['label'] }}</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br {{ $s['gradient'] }} text-white shadow-md group-hover:scale-110 transition-transform duration-300">
                        <x-dynamic-component :component="'heroicon-o-'.$s['icon']" class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">{{ $s['value'] }}</h3>
            </div>

            <div class="mt-4">
                <span class="{{ $s['badge_bg'] }} border text-[11px] font-semibold px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                    {{ $s['sub'] }}
                </span>
            </div>
        </div>
        @endforeach

        <!-- Stok Khusus (Rose/Red Gradient Accent) -->
        <div class="bg-gradient-to-br from-rose-50/90 via-white to-orange-50/50 border border-rose-200/80 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-rose-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-rose-600 text-[11px] font-bold tracking-wider uppercase">TOTAL STOK (KG)</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-md shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-rose-600 tracking-tight">{{ number_format($totalStokKg ?? 0) }}</h3>
            </div>

            <div class="mt-4">
                <span class="bg-rose-100/90 text-rose-700 border border-rose-200 text-[11px] font-semibold px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    <span>⚠ Kapasitas 85%</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Area Grafik & Persentase -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Stok -->
        <div class="lg:col-span-2 bg-white p-6 md:p-7 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-md shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Grafik Stok (Kg)</h3>
                        <p class="text-xs text-slate-400">Tren pergerakan panen bulanan tahun {{ now()->year }}</p>
                    </div>
                </div>
                <span class="text-[11px] font-bold border border-indigo-100 bg-indigo-50/80 text-indigo-700 px-3.5 py-1.5 rounded-xl uppercase tracking-wider shadow-sm">
                    Tahun {{ now()->year }}
                </span>
            </div>
            <div class="h-72 w-full">
                <canvas id="stokChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart & Breakdown -->
        <div class="bg-white p-6 md:p-7 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Jenis Kentang Terdaftar</h3>
                        <p class="text-xs text-slate-400">{{ $totalJenisKentang ?? 0 }} jenis kentang dalam master data.</p>
                    </div>
                </div>
                
                <div class="mt-4 flex flex-col items-center justify-center relative">
                    <div class="h-44 w-full relative flex items-center justify-center">
                        <canvas id="jenisChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                            <span class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $totalJenisKentang ?? 0 }}</span>
                            <span class="text-[10px] text-slate-400 font-bold tracking-wider uppercase">JENIS</span>
                        </div>
                    </div>

                    <div class="mt-5 w-full space-y-3.5">
                        @php
                            $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];
                        @endphp
                        @foreach($stokPerJenis as $index => $jenis)
                            @php
                                $color = $colors[$index % count($colors)];
                                $pct = $totalStokKg > 0 ? round(($jenis['total'] / $totalStokKg) * 100) : 0;
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $color }}"></span>
                                        <span class="text-slate-700 font-semibold">{{ $jenis['nama'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-400 font-medium">{{ number_format($jenis['total'], 0, ',', '.') }} Kg</span>
                                        <span class="font-bold text-slate-800 text-xs px-2 py-0.5 rounded-lg bg-slate-100">
                                            {{ $pct }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ max($pct, 2) }}%; background-color: {{ $color }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Area Chart
        const ctxStok = document.getElementById('stokChart').getContext('2d');
        
        let gradient = ctxStok.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
        gradient.addColorStop(0.5, 'rgba(99, 102, 241, 0.15)');
        gradient.addColorStop(1, 'rgba(238, 242, 255, 0.0)');

        new Chart(ctxStok, {
            type: 'line',
            data: {
                labels: {!! $grafikStokLabels !!},
                datasets: [{
                    label: 'Total Panen (Kg)',
                    data: {!! $grafikStokData !!},
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'extrabold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '📦 ' + context.parsed.y.toLocaleString('id-ID') + ' Kg Panen';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#64748b' }
                    },
                    y: {
                        border: { display: false, dash: [4, 4] },
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#64748b' }
                    }
                }
            }
        });

        // Donut Chart
        const ctxJenis = document.getElementById('jenisChart').getContext('2d');
        const jenisData = @json($stokPerJenis->pluck('total'));
        const jenisLabels = @json($stokPerJenis->pluck('nama'));
        
        new Chart(ctxJenis, {
            type: 'doughnut',
            data: {
                labels: jenisLabels,
                datasets: [{
                    data: jenisData,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { family: 'Plus Jakarta Sans' },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.toLocaleString('id-ID') + ' Kg';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
