@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl border shadow-sm">
    <h2 class="text-xl font-bold mb-4">Edit Data Jenis Kentang</h2>

    <form action="{{ route('admin.jenis_kentang.update', $item->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Nama Jenis</label>
            <input type="text" name="nama_jenis" value="{{ old('nama_jenis', $item->nama_jenis) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border rounded-lg px-3 py-2" required>{{ old('deskripsi', $item->deskripsi) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Satuan</label>
            <input type="text" name="satuan" value="{{ old('satuan', $item->satuan) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kualitas</label>
            <input type="text" name="kualitas" value="{{ old('kualitas', $item->kualitas) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <button class="bg-blue-900 text-white px-4 py-2 rounded-lg">Update</button>
    </form>
</div>
@endsection
