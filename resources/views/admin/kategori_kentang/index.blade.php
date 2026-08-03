@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Master Data: Kategori Kentang</h1>
            <p class="text-slate-500">Kelola data kategori kentang.</p>
        </div>
        <a href="{{ route('admin.kategori_kentang.create') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-white hover:bg-slate-700">Tambah Data</a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-100 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-slate-100/50">
                    <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-slate-600">No</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Nama Kategori</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Tipe Komoditas</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-slate-600">{{ $key + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $item->nama_kategori }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($item->tipe_komoditas == 'benih')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-bold">Benih (Tanam)</span>
                            @elseif($item->tipe_komoditas == 'olahan')
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-md text-xs font-bold">Olahan Industri</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-bold">Buah Konsumsi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.kategori_kentang.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">Edit</a>
                                <form action="{{ route('admin.kategori_kentang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>
@endsection