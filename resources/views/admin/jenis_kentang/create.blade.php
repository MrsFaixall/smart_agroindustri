@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl border shadow-sm">
    <h2 class="text-xl font-bold mb-4">Tambah Data Jenis Kentang</h2>

    <form action="{{ route('admin.jenis_kentang.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama Jenis</label>
            <input type="text" name="nama_jenis" value="{{ old('nama_jenis') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border rounded-lg px-3 py-2" required>{{ old('deskripsi') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Satuan</label>
            <input type="text" name="satuan" value="{{ old('satuan') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kualitas</label>
            <input type="text" name="kualitas" value="{{ old('kualitas') }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <button class="bg-blue-900 text-white px-4 py-2 rounded-lg">Simpan</button>
    </form>
</div>
@endsection
