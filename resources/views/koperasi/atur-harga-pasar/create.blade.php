@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('koperasi.atur-harga-pasar.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Atur Harga Pasar Baru</h1>
            <p class="text-xs text-slate-400">Tentukan harga jual baru untuk konsumen/mitra.</p>
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

    <form action="{{ route('koperasi.atur-harga-pasar.store') }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-600 to-teal-600 absolute top-0 left-0"></div>
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Varian Kentang</label>
                <select name="jenis_kentang_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-emerald-500 transition-all outline-none" required>
                    <option value="">Pilih Varietas</option>
                    @foreach($jenisKentangs as $jk)
                        <option value="{{ $jk->id }}">{{ $jk->nama_jenis }} ({{ ucfirst($jk->kategori) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Jual Koperasi (Rp / Kg)</label>
                <input type="number" name="harga" placeholder="Masukkan nominal harga jual" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-emerald-500 transition-all outline-none font-bold text-emerald-700" required>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('koperasi.atur-harga-pasar.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition-all">Simpan Harga Pasar</button>
        </div>
    </form>
</div>
@endsection
