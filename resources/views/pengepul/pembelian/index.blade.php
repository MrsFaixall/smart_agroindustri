@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Transaksi Pembelian</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola riwayat pembelian komoditas kentang dari petani.</p>
        </div>
        <a href="{{ route('pembelian.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#001842] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pembelian
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($totalTransaksi) }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Total Volume (Kg)</p>
            <h3 class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($totalJumlah, 2, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Total Nilai Pembelian</p>
            <h3 class="mt-2 text-2xl font-bold text-emerald-600">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-lg bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-800 shadow-sm">
            <span class="bg-emerald-500 text-white rounded-full p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-b-4 border-b-[#001842] overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">ID Pesanan</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500">Petani & Komoditas</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right">Volume (Kg)</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right">Total Harga</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pembelians as $pembelian)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            #{{ $pembelian->id }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                            {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-800">{{ $pembelian->petani->name ?? '-' }}</span>
                            <span class="block text-xs text-slate-500 mt-0.5">{{ $pembelian->jenisKentang->nama_jenis ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-slate-700">
                            {{ number_format($pembelian->jumlah_kg, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">
                            Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($pembelian->status === 'lunas')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('pembelian.edit', $pembelian->id) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Edit</a>
                            <form action="{{ route('pembelian.destroy', $pembelian->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            Belum ada transaksi pembelian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
