@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
            <p class="text-slate-500 text-sm">Kelola data pengguna dan hak akses sistem.</p>
        </div>

        <a href="{{ route('pengguna.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#001842] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-800 shadow-sm transition-all duration-300">
            <span class="bg-emerald-500 text-white rounded-full p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200 px-5 py-4 text-rose-800 shadow-sm transition-all duration-300">
            <span class="bg-rose-500 text-white rounded-full p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-slate-100/50">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">No.</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Email</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Role</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'super admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Admin</span>
                            @elseif($user->role === 'pengepul')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Pengepul</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Petani</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <a href="{{ route('pengguna.edit', $user->id) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition-colors">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <x-heroicon-o-folder-open class="w-10 h-10 text-slate-300" />
                                <p>Belum ada data pengguna.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
