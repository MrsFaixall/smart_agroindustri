@extends('layouts.app')

@section('content')
<div class="max-w-4xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Tambah Gudang</h1>
        <p class="text-slate-500">Isi informasi lokasi gudang baru.</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl bg-rose-50 px-4 py-3 text-rose-700">
            <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('gudang.store') }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Nama Gudang</span>
                <input type="text" name="nama_gudang" value="{{ old('nama_gudang') }}" class="w-full rounded-xl border px-4 py-3" required>
            </label>

            @include('petani.gudang._region-fields', ['regionValues' => [
                'provinsi' => old('provinsi'), 'kota' => old('kota'),
                'kecamatan' => old('kecamatan'), 'kelurahan' => old('kelurahan'),
            ]])

            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Alamat Lengkap (Jalan, RT/RW, Patokan)</span>
                <input type="text" name="alamat" value="{{ old('alamat') }}" class="w-full rounded-xl border px-4 py-3" required>
            </label>
            <label class="space-y-2"><span class="font-medium">Latitude</span><input type="number" step="0.00000001" name="latitude" value="{{ old('latitude') }}" class="w-full rounded-xl border px-4 py-3" required></label>
            <label class="space-y-2"><span class="font-medium">Longitude</span><input type="number" step="0.00000001" name="longitude" value="{{ old('longitude') }}" class="w-full rounded-xl border px-4 py-3" required></label>
            @include('petani.gudang._location-map', ['latitude' => old('latitude'), 'longitude' => old('longitude')])
        </div>
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('gudang.index') }}" class="rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-medium hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-xl bg-[#001842] px-6 py-2.5 text-sm font-bold text-white hover:bg-slate-800">Simpan Gudang</button>
        </div>
    </form>
</div>
@endsection
