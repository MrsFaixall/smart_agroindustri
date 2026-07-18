@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Edit Harga Kentang</h1>
        <p class="text-slate-500">Perbarui harga untuk jenis kentang.</p>
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

    <form action="{{ route('atur-harga.update', $price) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
        @csrf
        @method('PUT')

        <label class="space-y-2">
            <span class="font-medium">Jenis Kentang</span>
            <select name="jenis_kentang_id" class="w-full rounded-xl border px-4 py-3" required>
                <option value="">Pilih jenis kentang</option>
                @foreach($jenisKentangs as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $price->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                @endforeach
            </select>
        </label>

        <label class="space-y-2">
            <span class="font-medium">Harga (Rp)</span>
            <input type="number" step="0.01" name="harga" value="{{ old('harga', $price->harga) }}" class="w-full rounded-xl border px-4 py-3" required>
        </label>

        <div class="flex items-center gap-3">
            <a href="{{ route('atur-harga.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm">Batal</a>
            <button type="submit" class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white">Perbarui</button>
        </div>
    </form>
</div>
@endsection
