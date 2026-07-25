@extends('layouts.app')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('gudang.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Gudang</h1>
            <p class="text-xs text-slate-400">Perbarui informasi lokasi dan koordinat gudang.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-4 text-rose-700 shadow-sm">
            <ul class="list-disc list-inside text-sm space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gudang.update', $gudang) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-blue-600 to-indigo-600 absolute top-0 left-0"></div>
        @csrf 
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Nama Gudang</label>
                <input type="text" name="nama_gudang" value="{{ old('nama_gudang', $gudang->nama_gudang) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" required>
            </div>

            @include('petani.gudang._region-fields', ['regionValues' => [
                'provinsi' => old('provinsi', $gudang->provinsi), 'kota' => old('kota', $gudang->kota),
                'kecamatan' => old('kecamatan', $gudang->kecamatan), 'kelurahan' => old('kelurahan', $gudang->kelurahan),
            ]])

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Alamat Lengkap (Jalan, RT/RW, Patokan)</label>
                <input type="text" name="alamat" value="{{ old('alamat', $gudang->alamat) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Latitude</label>
                <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude', $gudang->latitude) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 font-mono text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Longitude</label>
                <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude', $gudang->longitude) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 font-mono text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" required>
            </div>

            @include('petani.gudang._location-map', ['latitude' => old('latitude', $gudang->latitude), 'longitude' => old('longitude', $gudang->longitude)])
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('gudang.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition-all">Perbarui Gudang</button>
        </div>
    </form>
</div>
@endsection
