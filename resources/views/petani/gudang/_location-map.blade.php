@php
    $latitude = $latitude ?? null;
    $longitude = $longitude ?? null;
@endphp

<div class="space-y-2 md:col-span-2">
    <div class="flex items-center justify-between gap-3">
        <div>
            <span class="font-medium">Pilih Lokasi Gudang di Peta</span>
            <p class="text-xs text-slate-500">Klik peta atau geser marker untuk mengisi latitude dan longitude secara otomatis.</p>
        </div>
        <div class="flex shrink-0 gap-2">
            <button type="button" id="find-region-location" class="rounded-lg bg-[#001842] px-3 py-2 text-xs font-medium text-white hover:bg-slate-800">Cari dari Wilayah</button>
            <button type="button" id="use-current-location" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Lokasi Saya</button>
        </div>
    </div>
    <div id="location-map" class="h-80 w-full overflow-hidden rounded-xl border border-slate-200"></div>
    <div id="location-confirmation" class="hidden rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-950">
        <p class="font-semibold">Lokasi ditemukan</p>
        <p id="location-confirmation-name" class="mt-1 text-xs text-blue-800"></p>
        <div class="mt-3 flex gap-2">
            <button type="button" id="confirm-location" class="rounded-lg bg-[#001842] px-3 py-2 text-xs font-semibold text-white">Gunakan lokasi ini</button>
            <button type="button" id="cancel-location" class="rounded-lg border border-blue-200 px-3 py-2 text-xs font-medium text-blue-800">Batal</button>
        </div>
    </div>
    <p id="location-map-error" class="hidden text-sm text-rose-600" role="alert"></p>
</div>

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const latitudeInput = document.querySelector('input[name="latitude"]');
        const longitudeInput = document.querySelector('input[name="longitude"]');
        const error = document.getElementById('location-map-error');
        const confirmation = document.getElementById('location-confirmation');
        const confirmationName = document.getElementById('location-confirmation-name');
        const searchUrl = @json(route('petani-gudang.cari-lokasi'));
        let suggestedLocation = null;
        const initialLatitudeValue = @json($latitude);
        const initialLongitudeValue = @json($longitude);
        const initialLatitude = Number(initialLatitudeValue);
        const initialLongitude = Number(initialLongitudeValue);
        const hasInitialLocation = initialLatitudeValue !== null && initialLatitudeValue !== ''
            && initialLongitudeValue !== null && initialLongitudeValue !== ''
            && Number.isFinite(initialLatitude) && Number.isFinite(initialLongitude);
        const map = L.map('location-map').setView(
            hasInitialLocation ? [initialLatitude, initialLongitude] : [-2.5489, 118.0149],
            hasInitialLocation ? 15 : 5
        );
        let marker;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const setLocation = (lat, lng, moveMap = true) => {
            if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
            latitudeInput.value = lat.toFixed(8);
            longitudeInput.value = lng.toFixed(8);
            const location = [lat, lng];
            if (marker) marker.setLatLng(location);
            else {
                marker = L.marker(location, { draggable: true }).addTo(map);
                marker.on('dragend', () => {
                    const point = marker.getLatLng();
                    setLocation(point.lat, point.lng, false);
                });
            }
            if (moveMap) map.setView(location, Math.max(map.getZoom(), 15));
            error.classList.add('hidden');
        };

        if (hasInitialLocation) setLocation(initialLatitude, initialLongitude, false);
        map.on('click', event => setLocation(event.latlng.lat, event.latlng.lng));
        [latitudeInput, longitudeInput].forEach(input => input.addEventListener('change', () => {
            setLocation(Number(latitudeInput.value), Number(longitudeInput.value));
        }));

        const wilayahTerpilih = () => ['kelurahan', 'kecamatan', 'kota', 'provinsi']
            .map(id => document.getElementById(id)?.value)
            .filter(Boolean)
            .join(', ');

        const cariLokasiWilayah = async (wilayah) => {
            if (!wilayah) {
                error.textContent = 'Pilih kelurahan/desa terlebih dahulu agar lokasi dapat dicari.';
                error.classList.remove('hidden');
                return;
            }
            error.classList.add('hidden');
            confirmation.classList.add('hidden');
            try {
                const response = await fetch(`${searchUrl}?${new URLSearchParams({ wilayah })}`, { headers: { Accept: 'application/json' } });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message);
                suggestedLocation = data;
                confirmationName.textContent = data.nama_lokasi;
                confirmation.classList.remove('hidden');
                map.setView([data.latitude, data.longitude], 15);
            } catch (exception) {
                error.textContent = exception.message || 'Lokasi tidak dapat ditemukan. Pilih titik peta secara manual.';
                error.classList.remove('hidden');
            }
        };

        document.addEventListener('gudang:wilayah-lengkap', event => {
            if (!latitudeInput.value && !longitudeInput.value) cariLokasiWilayah(event.detail.wilayah);
        });
        document.getElementById('find-region-location').addEventListener('click', () => cariLokasiWilayah(wilayahTerpilih()));
        document.getElementById('confirm-location').addEventListener('click', () => {
            if (suggestedLocation) setLocation(suggestedLocation.latitude, suggestedLocation.longitude);
            confirmation.classList.add('hidden');
        });
        document.getElementById('cancel-location').addEventListener('click', () => {
            suggestedLocation = null;
            confirmation.classList.add('hidden');
        });

        document.getElementById('use-current-location').addEventListener('click', () => {
            if (!navigator.geolocation) {
                error.textContent = 'Browser ini tidak mendukung pengambilan lokasi.';
                error.classList.remove('hidden');
                return;
            }
            navigator.geolocation.getCurrentPosition(
                position => setLocation(position.coords.latitude, position.coords.longitude),
                () => {
                    error.textContent = 'Lokasi tidak dapat diambil. Izinkan akses lokasi pada browser atau pilih titik di peta.';
                    error.classList.remove('hidden');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    });
    </script>
@endonce
