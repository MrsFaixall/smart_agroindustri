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
            <select name="kualitas" class="w-full border rounded-lg px-3 py-2" required>
                <option value="A" {{ (old('kualitas') ?? $item->kualitas) === 'A' ? 'selected' : '' }}>Grade A (Sangat Baik)</option>
                <option value="B" {{ in_array(old('kualitas') ?? $item->kualitas, ['B', 'baik']) ? 'selected' : '' }}>Grade B (Baik)</option>
                <option value="C" {{ in_array(old('kualitas') ?? $item->kualitas, ['C', 'buruk']) ? 'selected' : '' }}>Grade C (Kurang Baik)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="kategori_kentang_id" class="w-full border rounded-lg px-3 py-2" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ (old('kategori_kentang_id') ?? $item->kategori_kentang_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-blue-900 text-white px-4 py-2 rounded-lg">Update</button>
    </form>
</div>
@endsection
