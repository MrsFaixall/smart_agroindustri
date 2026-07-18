@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Edit Panen</h1>
        <p class="text-slate-500">Perbarui data panen.</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl bg-rose-50 px-4 py-3 text-rose-700">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('panen.update', $panen) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
        @csrf
        @method('PUT')
        <div class="grid gap-5 md:grid-cols-2">
            <label class="space-y-2">
                <span class="font-medium">Tanggal Panen</span>
                <input type="date" name="tanggal_panen" value="{{ old('tanggal_panen', $panen->tanggal_panen->format('Y-m-d')) }}" class="w-full rounded-xl border px-4 py-3" required>
            </label>
            <label class="space-y-2">
                <span class="font-medium">Jumlah (Kg)</span>
                <input type="number" step="0.01" name="jumlah_kg" value="{{ old('jumlah_kg', $panen->jumlah_kg) }}" class="w-full rounded-xl border px-4 py-3" required>
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Jenis Kentang</span>
                <select name="jenis_kentang_id" class="w-full rounded-xl border px-4 py-3" required>
                    <option value="">Pilih jenis kentang</option>
                    @foreach($jenisKentangs as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $panen->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Gudang</span>
                <select name="gudang_id" class="w-full rounded-xl border px-4 py-3" required>
                    <option value="">Pilih gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}" {{ old('gudang_id', $panen->gudang_id) == $gudang->id ? 'selected' : '' }}>{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Grade Kentang</span>
                <select name="grade" class="w-full rounded-xl border px-4 py-3" required>
                    <option value="A" {{ old('grade', $panen->grade) == 'A' ? 'selected' : '' }}>Grade A (Bagus)</option>
                    <option value="B" {{ old('grade', $panen->grade) == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                    <option value="C" {{ old('grade', $panen->grade) == 'C' ? 'selected' : '' }}>Grade C (Busuk)</option>
                </select>
            </label>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('panen.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm">Batal</a>
            <button type="submit" class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white">Perbarui</button>
        </div>
    </form>
</div>
@endsection
