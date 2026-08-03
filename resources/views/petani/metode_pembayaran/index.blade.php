@extends('layouts.app')

@section('content')
<div class="space-y-8">
        <x-petani-page-header 
        title="Metode Pembayaran" 
        subtitle="Kelola rekening bank, e-wallet, dan QRIS untuk penerimaan transaksi hasil panen."
        icon="credit-card"
        color="indigo"
        actionUrl="{{ route('metode-pembayaran.create') }}"
        actionText="Tambah Metode Baru"
        actionIcon="plus"
    />

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" /> {{ session('success') }}
        </div>
    @endif

    <!-- Search Section -->
    <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100">
        <form action="{{ route('metode-pembayaran.index') }}" method="GET" class="flex gap-2 w-full max-w-md">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" 
                    placeholder="Cari nama petani atau bank...">
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-md shadow-blue-600/20">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('metode-pembayaran.index') }}" class="inline-flex items-center px-3 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
        <div class="overflow-x-auto">
    <x-petani-table-filter placeholder="Cari data metode pembayaran..." />

                <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Provider / Bank</th>
                        <th class="px-6 py-4">Nama Pemilik</th>
                        <th class="px-6 py-4">No. Rekening</th>
                        <th class="px-6 py-4">QRIS</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($methods as $method)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs">
                                    💳 {{ $method->kategori ?? 'Transfer Bank' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $method->bank }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $method->user->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">A/N: {{ $method->atas_nama }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-indigo-700 tracking-wide">{{ $method->no_rekening }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                @if($method->qr_image)
                                    <a href="{{ asset('storage/' . $method->qr_image) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat QR
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('metode-pembayaran.edit', $method) }}" class="rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                                    <form action="{{ route('metode-pembayaran.destroy', $method) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus metode pembayaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                Belum ada metode pembayaran yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.pagination', ['paginator' => $methods, 'label' => 'metode pembayaran'])
</div>
@endsection
