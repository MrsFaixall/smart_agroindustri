@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Metode Pembayaran</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola rekening bank, e-wallet, dan QRIS untuk menerima pembayaran.</p>
        </div>
        <a href="{{ route('metode-pembayaran.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#001842] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Baru
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-lg bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-800 shadow-sm">
            <span class="bg-emerald-500 text-white rounded-full p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <form action="{{ route('metode-pembayaran.index') }}" method="GET" class="flex gap-2 w-full max-w-md">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" 
                    placeholder="Cari nama petani...">
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('metode-pembayaran.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-b-4 border-b-[#001842] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Provider/Bank</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Nama Petani</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">No. Rekening</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">QRIS</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                    </tr>
                </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($methods as $method)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ $method->kategori ?? 'Transfer Bank' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $method->bank }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">{{ $method->user->name ?? '-' }}</div>
                            <div class="text-xs text-slate-500">A/N: {{ $method->atas_nama }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600">{{ $method->no_rekening }}</td>
                        <td class="px-6 py-4 text-slate-500">
                            @if($method->qr_image)
                                <a href="{{ asset('storage/' . $method->qr_image) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat QR
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 flex justify-end gap-3">
                            <a href="{{ route('metode-pembayaran.edit', $method) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Edit</a>
                            <form action="{{ route('metode-pembayaran.destroy', $method) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus metode pembayaran ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Belum ada metode pembayaran yang terdaftar.
                        </td>
                    </tr>
                @endforelse
        </table>
        </div>
    </div>

    @include('partials.pagination', ['paginator' => $methods, 'label' => 'metode pembayaran'])
</div>
@endsection
