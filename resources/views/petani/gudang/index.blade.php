@extends('layouts.app')

@section('content')
<div class="space-y-8">
        <x-petani-page-header 
        title="Manajemen Gudang" 
        subtitle="Pantau dan kelola lokasi serta kapasitas penyimpanan logistik di seluruh wilayah."
        icon="building-storefront"
        color="blue"
        bgImage="bg-gudang.jpg"
        actionUrl="{{ route('petani-gudang.create') }}"
        actionText="Tambah Gudang Baru"
        actionIcon="plus"
    />

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" /> {{ session('success') }}
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Gudang</p>
                <p class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ str_pad($totalGudang, 2, '0', STR_PAD_LEFT) }} <span class="text-sm font-semibold text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Kapasitas Global</p>
                <p class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ number_format($kapasitasGlobal / 1000, 0, ',', '.') }} <span class="text-sm font-semibold text-slate-400">Ton</span></p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 border border-amber-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Gudang Aktif</p>
                <p class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ str_pad($gudangAktif, 2, '0', STR_PAD_LEFT) }} <span class="text-sm font-semibold text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-50/80 via-white to-orange-50/40 border border-rose-200/80 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex items-center gap-4">
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-md shadow-rose-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-rose-500 uppercase">Hampir Penuh</p>
                <p class="text-2xl font-extrabold text-rose-600 tracking-tight">{{ str_pad($gudangPenuh, 2, '0', STR_PAD_LEFT) }} <span class="text-sm font-semibold text-rose-400">Unit</span></p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <h2 class="flex items-center gap-2 font-bold text-lg text-slate-800">Daftar Inventaris Gudang</h2>
                            <p class="text-xs text-slate-400">Kapasitas & lokasi gudang operasional</p>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto flex-1">
    <x-petani-table-filter placeholder="Cari data manajemen gudang..." />

                        <table class="w-full text-left">
                        <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Nama Gudang</th>
                                <th class="px-6 py-4">Wilayah</th>
                                <th class="px-6 py-4">Kapasitas (KG)</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($gudangs as $gudang)
                                <tr class="hover:bg-slate-50/80 transition-all group">
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-800">{{ $gudang->nama_gudang }}</p>
                                        <p class="text-[11px] text-indigo-600 font-semibold mt-0.5">👤 Petani: {{ $gudang->user->name ?? 'Belum Diketahui' }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5 max-w-[200px] truncate" title="{{ $gudang->alamat }}">{{ $gudang->alamat }}</p>
                                        <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $gudang->latitude }}, {{ $gudang->longitude }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        <div class="space-y-0.5">
                                            <p class="font-medium text-slate-700">{{ $gudang->provinsi ?? '-' }}, {{ $gudang->kota ?? '-' }}</p>
                                            <p class="text-xs text-slate-400">{{ $gudang->kecamatan ?? '-' }}, {{ $gudang->kelurahan ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-end justify-between mb-1.5">
                                            <span class="text-xs font-bold text-slate-800">{{ number_format($gudang->kapasitas_terpakai, 0, ',', '.') }} / <span class="text-slate-400 font-medium">{{ number_format($gudang->kapasitas_max, 0, ',', '.') }}</span></span>
                                            <span class="text-[10px] font-bold {{ $gudang->persentase_kapasitas >= 90 ? 'text-rose-600' : 'text-slate-500' }}">{{ $gudang->persentase_kapasitas }}%</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $gudang->persentase_kapasitas >= 90 ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-emerald-500 to-teal-500' }} rounded-full transition-all duration-500" style="width: {{ min($gudang->persentase_kapasitas, 100) }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($gudang->persentase_kapasitas >= 90)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-rose-50 border border-rose-200 text-rose-700 shadow-sm">
                                                ⚠ Hampir Penuh
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm">
                                                ✓ Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex justify-center items-center gap-1.5">
                                            <a href="{{ route('petani-gudang.edit', $gudang) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('petani-gudang.destroy', $gudang) }}" method="POST" class="inline-block" onsubmit="return confirm(@js($gudang->stoks->count() || $gudang->panens->count() ? 'Gudang ini memiliki '.$gudang->stoks->count().' stok dan '.$gudang->panens->count().' data panen. Semua data terkait akan ikut dihapus. Lanjutkan?' : 'Hapus gudang ini?'))">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <p class="text-sm font-medium">Belum ada data gudang yang tersimpan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column (Map) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden flex flex-col min-h-[500px]">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Visualisasi Lokasi</h2>
                        <p class="text-xs text-slate-400">Peta distribusi lokasi gudang</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-full uppercase">{{ $mapGudangs->count() }} Gudang</span>
            </div>
            
            <div class="flex-1 relative bg-slate-50">
                <div id="map" class="absolute inset-0 z-10"></div>

                <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-md border border-slate-100 rounded-2xl p-3 text-[11px] text-slate-600 text-center z-20 shadow-md">
                    📍 Klik pin pada peta untuk melihat detail spesifik gudang dan rute logistik.
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
