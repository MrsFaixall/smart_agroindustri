@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <h2 class="text-2xl font-extrabold text-slate-800 mb-6">Buat Pengajuan Benih</h2>

        @if($errors->any())
        <div class="p-4 mb-6 rounded-xl bg-rose-50 text-rose-700 font-semibold border border-rose-200">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pengajuan-benih.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Koperasi Tujuan</label>
                <select name="koperasi_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">-- Pilih Koperasi --</option>
                    @foreach($koperasis as $koperasi)
                        <option value="{{ $koperasi->id }}">{{ $koperasi->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Pengajuan</label>
                <select name="tipe_pengajuan" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="meminta">Meminta (Bantuan / Subsidi)</option>
                    <option value="membeli">Membeli (Transaksi Berbayar)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Benih</label>
                <select name="jenis_kentang_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">-- Pilih Jenis Benih --</option>
                    @foreach($jenisKentangs as $jk)
                        <option value="{{ $jk->id }}">{{ $jk->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah (Kg)</label>
                <input type="number" step="0.01" name="jumlah_kg" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" placeholder="Misal: 50.5">
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <a href="{{ route('pengajuan-benih.petani') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-all">Batal</a>
                <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg shadow-teal-600/30 transition-all">Ajukan Benih</button>
            </div>
        </form>
    </div>
</div>
@endsection
