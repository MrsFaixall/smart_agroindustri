@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Gudang</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau dan kelola kapasitas stok logistik di seluruh wilayah.</p>
        </div>
        <a href="{{ route('gudang.create') }}" 
           class="inline-flex items-center gap-2 rounded-xl bg-[#001842] px-5 py-2.5 text-white text-sm font-semibold hover:bg-slate-800 transition-all shadow-lg shadow-blue-900/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Gudang Baru
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Gudang</p>
                <p class="text-xl font-bold text-slate-900">{{ str_pad($totalGudang, 2, '0', STR_PAD_LEFT) }} Unit</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Kapasitas Global</p>
                <p class="text-xl font-bold text-slate-900">{{ number_format($kapasitasGlobal / 1000, 0, ',', '.') }} Ton</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Gudang Aktif</p>
                <p class="text-xl font-bold text-slate-900">{{ str_pad($gudangAktif, 2, '0', STR_PAD_LEFT) }} Unit</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-rose-100 shadow-sm flex items-center gap-4 bg-rose-50/30">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-wider text-rose-400 uppercase">Penuh / Limit</p>
                <p class="text-xl font-bold text-rose-700">{{ str_pad($gudangPenuh, 2, '0', STR_PAD_LEFT) }} Unit</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h2 class="font-bold text-slate-800">Daftar Inventaris Gudang</h2>
                    </div>
                    <div class="flex gap-2">
                        <button class="p-2 border border-slate-200 rounded-lg text-slate-400 hover:bg-slate-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                        <button class="p-2 border border-slate-200 rounded-lg text-slate-400 hover:bg-slate-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Nama Gudang</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Wilayah</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Kapasitas (KG)</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($gudangs as $gudang)
                                <tr class="hover:bg-slate-50/50 transition-all group">
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">{{ $gudang->nama_gudang }}</p>
                                        <p class="text-[11px] text-slate-500 mt-1 max-w-[200px] truncate" title="{{ $gudang->alamat }}">{{ $gudang->alamat }}</p>
                                        <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $gudang->latitude }}, {{ $gudang->longitude }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        <div class="space-y-1">
                                            <p>{{ $gudang->provinsi ?? '-' }}, {{ $gudang->kota ?? '-' }}</p>
                                            <p class="text-[11px] text-slate-400">{{ $gudang->kecamatan ?? '-' }}, {{ $gudang->kelurahan ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-end justify-between mb-2">
                                            <span class="text-xs font-bold text-slate-800">{{ number_format($gudang->kapasitas_terpakai, 0, ',', '.') }} / <span class="text-slate-400 font-medium">{{ number_format($gudang->kapasitas_max, 0, ',', '.') }}</span></span>
                                            <span class="text-[10px] font-bold {{ $gudang->persentase_kapasitas >= 90 ? 'text-rose-500' : 'text-slate-500' }}">{{ $gudang->persentase_kapasitas }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $gudang->persentase_kapasitas >= 90 ? 'bg-rose-500' : 'bg-emerald-500' }} rounded-full" style="width: {{ min($gudang->persentase_kapasitas, 100) }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($gudang->persentase_kapasitas >= 90)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-600">
                                                Hampir Penuh
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600">
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center space-x-1">
                                        <a href="{{ route('gudang.edit', $gudang) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('gudang.destroy', $gudang) }}" method="POST" class="inline-block" onsubmit="return confirm(@js($gudang->stoks->count() || $gudang->panens->count() ? 'Gudang ini memiliki '.$gudang->stoks->count().' stok dan '.$gudang->panens->count().' data panen. Semua data terkait akan ikut dihapus. Lanjutkan?' : 'Hapus gudang ini?'))">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <p class="text-sm">Belum ada data gudang yang tersimpan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Menampilkan {{ $gudangs->count() }} gudang</span>
                    <div class="flex gap-2">
                        <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50">Sebelumnya</button>
                        <button class="px-3 py-1.5 rounded-lg bg-[#001842] text-white font-medium hover:bg-slate-800">Selanjutnya</button>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <h3 class="font-bold text-slate-800 text-sm">Aktivitas Bongkar Muat</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Pengiriman Keluar - T0982</p>
                                    <p class="text-[10px] text-slate-500">Gudang Sukabumi → Jakarta</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400">10 Menit Lalu</span>
                        </div>
                        <div class="p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Penerimaan Hasil Panen - H7712</p>
                                    <p class="text-[10px] text-slate-500">Petani Cisarua → Gudang Utama</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400">1 Jam Lalu</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <h3 class="font-bold text-slate-800 text-sm">Analisis Kapasitas Real-time</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Prediksi penggunaan ruang gudang berdasarkan siklus panen bulan ini.</p>
                    
                    <div class="h-32 flex items-end justify-between gap-2 px-2">
                        <div class="w-full bg-slate-200 rounded-t-sm h-[40%]"></div>
                        <div class="w-full bg-slate-200 rounded-t-sm h-[60%]"></div>
                        <div class="w-full bg-[#001842] rounded-t-sm h-[80%] relative group">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">Hari Ini</div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-t-sm h-[50%]"></div>
                        <div class="w-full bg-slate-200 rounded-t-sm h-[90%]"></div>
                        <div class="w-full bg-slate-200 rounded-t-sm h-[70%]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Map) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <h2 class="font-bold text-slate-800">Visualisasi Lokasi</h2>
                </div>
                <span class="text-[10px] font-bold bg-[#001842] text-white px-2 py-1 rounded uppercase">{{ $mapGudangs->count() }} Gudang</span>
            </div>
            
            <div class="flex-1 relative bg-slate-50">
                <div id="map" class="absolute inset-0 z-10"></div>

                <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-sm border border-slate-100 rounded-xl p-3 text-[10px] text-slate-500 text-center z-20 shadow-sm pointer-events-none">
                    * Klik pin pada peta untuk melihat detail spesifik gudang dan rute logistik terdekat.
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
    // Initialize map
    // Centered around West Java approximately
    var map = L.map('map').setView([-6.914744, 107.609810], 8);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker styling (custom icon to match design if possible, or default)
    var defaultIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Data marker dibuat khusus oleh controller agar selalu sama dengan daftar gudang.
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
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700">${g.kapasitas_terpakai} / ${g.kapasitas_max} Kg</span>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            bounds.push([g.latitude, g.longitude]);
        }
    });

    // If there are markers, fit the map to show all of them
    if (bounds.length > 0) {
        map.fitBounds(bounds, {padding: [50, 50]});
    }
});
</script>
@endsection
