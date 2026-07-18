@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Master Data: Jenis Kentang</h1>
            <p class="text-slate-500">Kelola data jenis kentang.</p>
        </div>
        <a href="{{ route('admin.jenis_kentang.create') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-white hover:bg-slate-700">Tambah Data</a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-100 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-slate-100/50">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Jenis</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Deskripsi</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Satuan</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Kualitas</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($data as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-800">{{ $item->nama_jenis }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ Str::limit($item->deskripsi, 60) }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $item->satuan }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $item->kualitas }}</td>
                    <td class="px-6 py-4 flex justify-end gap-2">
                        <a href="{{ route('admin.jenis_kentang.edit', $item->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                        <form action="{{ route('admin.jenis_kentang.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jenis kentang ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition-colors">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <x-heroicon-o-folder-open class="w-10 h-10 text-slate-300" />
                            <p>Belum ada data jenis kentang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection