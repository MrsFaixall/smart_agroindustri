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
            <select name="kualitas" class="w-full border rounded-lg px-3 py-2" required>
                <option value="A" {{ old('kualitas') == 'A' ? 'selected' : '' }}>Grade A (Sangat Baik)</option>
                <option value="B" {{ old('kualitas') == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                <option value="C" {{ old('kualitas') == 'C' ? 'selected' : '' }}>Grade C (Kurang Baik)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="kategori" class="w-full border rounded-lg px-3 py-2" required>
                <option value="benih_hulu" {{ old('kategori') == 'benih_hulu' ? 'selected' : '' }}>Benih Hulu</option>
                <option value="kentang_konsumsi" {{ old('kategori') == 'kentang_konsumsi' ? 'selected' : '' }}>Kentang Konsumsi</option>
            </select>
        </div>
        <button class="bg-blue-900 text-white px-4 py-2 rounded-lg">Simpan</button>
    </form>
</div>
@endsection
