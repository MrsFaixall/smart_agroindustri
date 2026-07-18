@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl border shadow-sm">
    <h2 class="text-xl font-bold mb-4">Tambah Data BBM</h2>

    <form action="{{ route('admin.bbm.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama BBM</label>
            <input type="text" name="nama_bbm" value="{{ old('nama_bbm') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Jumlah Liter</label>
            <input type="number" step="0.01" name="jumlah_liter" value="{{ old('jumlah_liter') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">KM</label>
            <input type="number" step="0.01" name="km" value="{{ old('km') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Harga</label>
            <input type="number" step="0.01" name="harga" value="{{ old('harga') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <button class="bg-blue-900 text-white px-4 py-2 rounded-lg">Simpan</button>
    </form>
</div>
@endsection
