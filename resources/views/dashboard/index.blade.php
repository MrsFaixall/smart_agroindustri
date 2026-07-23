@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Bagian Atas -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Utama</h2>
            <p class="text-gray-500">Selamat datang kembali. Berikut adalah ringkasan operasional logistik hari ini.</p>
        </div>
        <div class="flex gap-3">
            <button class="border px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 transition">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5"/> Ekspor Data
            </button>
            <a href="{{ route('panen.create') }}" class="bg-[#0f172a] text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-950 transition">
                <span class="font-bold">+</span> Input Panen
            </a>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @php
            $stats = [
                ['label' => 'TOTAL GUDANG', 'value' => number_format($totalGudang ?? 0), 'sub' => '↗ +2 Gudang baru', 'icon' => 'home'],
                ['label' => 'TOTAL PETANI', 'value' => number_format($totalPetani ?? 0), 'sub' => '↗ +12 bulan ini', 'icon' => 'user-group'],
                ['label' => 'TOTAL KOPERASI', 'value' => number_format($totalKoperasi ?? 0), 'sub' => '— Stabil', 'icon' => 'truck'],
                ['label' => 'TOTAL PANEN', 'value' => number_format(($totalPanenKg ?? 0) / 1000, 2, ',', '.').' Ton', 'sub' => number_format($totalPanenKg ?? 0, 0, ',', '.').' Kg tercatat', 'icon' => 'archive-box'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white p-6 rounded-2xl border shadow-sm relative overflow-hidden">
            <p class="text-gray-400 text-[10px] font-bold tracking-wider">{{ $s['label'] }}</p>
            <h3 class="text-3xl font-bold mt-1">{{ $s['value'] }}</h3>
            <p class="text-xs text-gray-400 mt-2">{{ $s['sub'] }}</p>
            <div class="absolute top-6 right-6 text-blue-900 opacity-20">
                <x-dynamic-component :component="'heroicon-o-'.$s['icon']" class="w-8 h-8"/>
            </div>
        </div>
        @endforeach

        <!-- Stok Khusus -->
        <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100 shadow-sm">
            <p class="text-orange-600 text-[10px] font-bold tracking-wider">TOTAL STOK (KG)</p>
            <h3 class="text-3xl font-bold text-orange-600 mt-1">{{ number_format($totalStokKg ?? 0) }}</h3>
            <p class="text-xs text-orange-400 mt-2">⚠ Kapasitas 85%</p>
        </div>
    </div>

    <!-- Area Grafik & Persentase -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Grafik -->
        <div class="col-span-2 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Grafik Stok (Kg)</h3>
                    <p class="text-xs text-slate-400">Tren pergerakan panen bulanan {{ now()->year }}</p>
                </div>
                <span class="text-[10px] font-bold border border-slate-200 bg-slate-50 text-slate-500 px-3 py-1.5 rounded-lg uppercase tracking-wider">Tahun Ini</span>
            </div>
            <div class="h-64 w-full">
                <canvas id="stokChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Jenis Kentang Terdaftar</h3>
                <p class="text-xs text-slate-400 mb-6">{{ $totalJenisKentang ?? 0 }} jenis kentang tersedia dalam master data.</p>
            </div>
            
            <div class="flex-1 flex flex-col items-center justify-center relative">
                <div class="h-48 w-full relative flex items-center justify-center">
                    <canvas id="jenisChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                        <span class="text-2xl font-bold text-slate-800">{{ $totalJenisKentang ?? 0 }}</span>
                        <span class="text-[10px] text-slate-400 font-bold tracking-wider">JENIS</span>
                    </div>
                </div>
                <div class="mt-6 w-full space-y-3">
                    @php
                        $colors = ['#1e3a8a', '#16a34a', '#eab308', '#dc2626', '#9333ea'];
                    @endphp
                    @foreach($stokPerJenis as $index => $jenis)
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                                <span class="text-slate-600 font-medium">{{ $jenis['nama'] }}</span>
                            </div>
                            <span class="font-bold text-slate-800">
                                @if($totalStokKg > 0)
                                    {{ round(($jenis['total'] / $totalStokKg) * 100) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Quote -->
    <!-- <div class="bg-[#0f172a] text-white p-8 rounded-3xl flex items-center gap-8 shadow-lg shadow-slate-200">
        <span class="text-5xl font-serif opacity-50">99</span>
        <p class="text-lg italic text-slate-300">"Digitalisasi rantai pasok pertanian untuk meningkatkan efisiensi, transparansi, dan akurasi pengelolaan data logistik."</p>
    </div> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Area Chart
        const ctxStok = document.getElementById('stokChart').getContext('2d');
        
        // Create gradient
        let gradient = ctxStok.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(30, 58, 138, 0.2)'); // blue-900
        gradient.addColorStop(1, 'rgba(30, 58, 138, 0)');

        new Chart(ctxStok, {
            type: 'line',
            data: {
                labels: {!! $grafikStokLabels !!},
                datasets: [{
                    label: 'Total Panen (Kg)',
                    data: {!! $grafikStokData !!},
                    borderColor: '#1e3a8a',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#1e3a8a',
                    pointBorderWidth: 2,
                    pointRadius: 4,
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
                        titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('id-ID') + ' Kg';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans' }, color: '#94a3b8' }
                    },
                    y: {
                        border: { display: false },
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Plus Jakarta Sans' }, color: '#94a3b8' }
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
                    backgroundColor: ['#1e3a8a', '#16a34a', '#eab308', '#dc2626', '#9333ea'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
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
