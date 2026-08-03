@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl border shadow-sm">
    <h2 class="text-xl font-bold mb-4">Tambah Data Kategori Kentang</h2>

                <form action="{{ route('admin.kategori_kentang.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Tipe Komoditas</label>
                    <select name="tipe_komoditas" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="benih">Benih (Untuk Penanaman)</option>
                        <option value="konsumsi">Buah Konsumsi (Untuk Penjualan/Pasar)</option>
                        <option value="olahan">Kentang Olahan (Industri/Lainnya)</option>
                    </select>
                </div>
                <button type="submit" class="bg-[#001842] text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition">Simpan</button>
                <a href="{{ route('admin.kategori_kentang.index') }}" class="ml-4 text-slate-500 hover:text-slate-700">Batal</a>
            </form>
</div>
@endsection
