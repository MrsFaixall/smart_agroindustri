@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-full bg-[#001842] text-white flex items-center justify-center font-bold text-lg">
            4
        </div>
        <h1 class="text-2xl font-bold text-[#001842] uppercase tracking-wide">Laporan</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8">
        
        <!-- Filter Header -->
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800">Ringkasan Laporan</h2>
            
            <form action="{{ route('laporan.index') }}" method="GET" class="flex gap-2">
                <select name="month" class="rounded-xl border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 focus:border-[#001842] focus:ring-[#001842] cursor-pointer outline-none" onchange="this.form.submit()">
                    @foreach(['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'] as $num => $name)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" class="rounded-xl border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 focus:border-[#001842] focus:ring-[#001842] cursor-pointer outline-none" onchange="this.form.submit()">
                    @for($y = date('Y') - 2; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <!-- Total Pembelian -->
            <div class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Total Pembelian</span>
                </div>
                <span class="font-bold text-slate-900 text-lg">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</span>
            </div>

            <!-- Total Stok Akhir -->
            <div class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="text-[#001842]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Total Stok Akhir</span>
                </div>
                <span class="font-bold text-slate-900 text-lg">{{ number_format($totalStokAkhir, 0, ',', '.') }} Kg</span>
            </div>

            <!-- Total Pembayaran -->
            <div class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="text-emerald-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Total Pembayaran</span>
                </div>
                <span class="font-bold text-slate-900 text-lg">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
            </div>

            <!-- Laba Kotor -->
            <div class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Laba Kotor (Estimasi)</span>
                </div>
                <span class="font-bold text-slate-900 text-lg">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Chart -->
        <div>
            <h2 class="text-sm font-bold text-slate-800 mb-6">Grafik Pembelian (Rp)</h2>
            <div class="relative w-full" style="height: 280px;">
                <canvas id="pembelianChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('pembelianChart').getContext('2d');
        
        // Data dari backend
        const rawChartData = @json(array_values($chartData));
        // Array bulan
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        
        // Filter bulan yang akan ditampilkan (hanya sampai bulan yang dipilih atau maksimal data bulan ini)
        // Di mockup, grafik sumbu X nya hanya Jan-Mei.
        // Kita bisa ambil index bulan terpilih (0 - 11)
        const currentMonthIndex = {{ (int)$month - 1 }};
        
        // Kita bisa batasi chart menampilkan data dari Januari sampai bulan yang dipilih
        const chartLabels = labels.slice(0, currentMonthIndex + 1);
        const chartData = rawChartData.slice(0, currentMonthIndex + 1);
        
        // Format label sumbu Y dalam Juta (jt)
        const formatCurrency = (value) => {
            if(value === 0) return '0';
            return (value / 1000000) + ' jt';
        };

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels, // Label dinamis dari Jan s/d bulan terpilih
                datasets: [{
                    label: 'Total Pembelian',
                    data: chartData,
                    borderColor: '#2563eb', // Blue-600
                    backgroundColor: 'rgba(37, 99, 235, 0.08)', // Light blue for fill
                    borderWidth: 2,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.2 // Slightly smooth curve
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan legend agar mirip desain
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f8fafc', // Sangat pudar
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                return formatCurrency(value);
                            },
                            color: '#64748b', // Slate-500
                            font: {
                                size: 11,
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            padding: 10,
                            maxTicksLimit: 6
                        }
                    },
                    x: {
                        grid: {
                            display: false, // Hilangkan grid vertikal
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11,
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            padding: 10
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
