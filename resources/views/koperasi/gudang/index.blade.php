@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>Fasilitas & Inventaris Koperasi (Terpisah)</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Gudang & Stok Koperasi</h1>
            <p class="text-slate-300 text-sm max-w-xl">Kelola lokasi gudang Koperasi serta monitor stok Benih & Kentang Konsumsi yang dimiliki.</p>
        </div>
        <div class="relative z-10 flex gap-2">
            <a href="{{ route('koperasi.gudang-stok.create-gudang') }}" 
               class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5">
                + Tambah Gudang Koperasi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" /> {{ session('success') }}
        </div>
    @endif

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Gudang Koperasi</p>
                <p class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ str_pad($gudangs->count(), 2, '0', STR_PAD_LEFT) }} <span class="text-sm font-semibold text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Akumulasi Stok</p>
                <p class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalStok, 0, ',', '.') }} <span class="text-sm font-semibold text-slate-400">Kg</span></p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40 border border-purple-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-md shadow-purple-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Utilitas Gudang</p>
                <p class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $utilitasGudang }}%</p>
            </div>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Gudang & Stok Lists -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Gudang Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Daftar Gudang Koperasi</h2>
                        <p class="text-xs text-slate-400">Pusat logistik penyimpanan Benih & Buah Koperasi</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Nama Gudang</th>
                                <th class="px-6 py-4">Wilayah / Alamat</th>
                                <th class="px-6 py-4">Kapasitas (Kg)</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($gudangs as $g)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $g->nama_gudang }}</td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $g->alamat }}<br>
                                        <span class="text-xs font-semibold text-slate-400">
                                            {{ collect([$g->kelurahan, $g->kecamatan, $g->kota])->filter()->implode(', ') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-bold text-slate-700">{{ number_format($g->stoks->sum('jumlah_stok'), 0, ',', '.') }}</span>
                                            <span class="text-slate-400">/</span>
                                            <span class="text-slate-400 font-mono text-xs">{{ number_format($g->kapasitas_max, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('koperasi.gudang-stok.edit-gudang', $g->id) }}" class="p-1 text-slate-400 hover:text-blue-600 transition-colors">✏️ Edit</a>
                                            <form action="{{ route('koperasi.gudang-stok.destroy-gudang', $g->id) }}" method="POST" onsubmit="return confirm('Hapus gudang ini beserta seluruh stok di dalamnya?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 text-rose-500 hover:text-rose-700 transition-colors">🗑 Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400">Belum ada gudang koperasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>



        <!-- Right Column: Leaflet Map -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden flex flex-col min-h-[450px]">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-lg text-slate-800">Visualisasi Gudang</h2>
                    <p class="text-xs text-slate-400">Distribusi lokasi Gudang Koperasi</p>
                </div>
                <span class="text-[10px] font-bold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-full uppercase">{{ $gudangs->count() }} Gudang</span>
            </div>
            
            <div class="flex-1 relative bg-slate-50">
                <div id="map" class="absolute inset-0 z-10"></div>
                <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-md border border-slate-100 rounded-2xl p-3 text-[11px] text-slate-600 text-center z-20 shadow-md">
                    📍 Lokasi Gudang Manajemen Koperasi (Terpisah).
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([-6.914744, 107.609810], 8);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var defaultIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var gudangs = @json($mapGudangs);
    var bounds = [];

    gudangs.forEach(function(g) {
        if(Number.isFinite(g.latitude) && Number.isFinite(g.longitude)) {
            var marker = L.marker([g.latitude, g.longitude], {icon: defaultIcon}).addTo(map);
            var escapeHtml = function(value) {
                return String(value || '-').replace(/[&<>'"]/g, function(character) {
                    return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character];
                });
            };
            
            var popupContent = `
                <div class="p-2 min-w-[150px]">
                    <h4 class="font-bold text-slate-800 mb-1">${escapeHtml(g.nama_gudang)}</h4>
                    <p class="text-xs text-slate-500 mb-1">${escapeHtml(g.alamat)}</p>
                    <p class="text-xs text-slate-400 mb-2">${escapeHtml(g.wilayah)}</p>
                    <div class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>${g.kapasitas_terpakai} / ${g.kapasitas_max} Kg</span>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            bounds.push([g.latitude, g.longitude]);
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, {padding: [50, 50]});
    }
});
</script>
@endsection
