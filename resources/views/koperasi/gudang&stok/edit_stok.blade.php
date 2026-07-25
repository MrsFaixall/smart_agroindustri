@extends('layouts.app')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('koperasi.gudang-stok.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Penyesuaian Stok Koperasi</h1>
            <p class="text-xs text-slate-400">Atur jumlah stok fisik dan porsi siap dijual di Gudang Koperasi.</p>
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

    <form action="{{ route('koperasi.gudang-stok.update-stok', $stok->id) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-blue-600 to-indigo-600 absolute top-0 left-0"></div>
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Pilih Gudang Koperasi</label>
                <select name="gudang_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 transition-all outline-none" required>
                    @foreach($gudangs as $g)
                        <option value="{{ $g->id }}" {{ $stok->gudang_id == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Varietas Komoditas</label>
                <select name="jenis_kentang_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 transition-all outline-none" required>
                    @foreach($jenisKentangs as $jk)
                        <option value="{{ $jk->id }}" {{ $stok->jenis_kentang_id == $jk->id ? 'selected' : '' }}>{{ $jk->nama_jenis }} ({{ ucfirst($jk->kategori) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Grade</label>
                <select name="grade" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 transition-all outline-none" required>
                    <option value="A" {{ $stok->grade == 'A' ? 'selected' : '' }}>Grade A</option>
                    <option value="B" {{ $stok->grade == 'B' ? 'selected' : '' }}>Grade B</option>
                    <option value="C" {{ $stok->grade == 'C' ? 'selected' : '' }}>Grade C</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Jumlah Stok Fisik (Kg)</label>
                <input type="number" step="0.01" name="jumlah_stok" value="{{ old('jumlah_stok', $stok->jumlah_stok) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 transition-all outline-none" required>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Porsi Siap Jual ke PT Champ/Pasar (Kg)</label>
                <input type="number" step="0.01" name="stok_dijual" value="{{ old('stok_dijual', $stok->stok_dijual) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 transition-all outline-none" required>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('koperasi.gudang-stok.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition-all">Simpan Penyesuaian</button>
        </div>
    </form>
</div>
@endsection
