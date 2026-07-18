@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-400 mb-1">
                Beranda / Stok
            </p>

            <h1 class="text-3xl font-bold text-slate-900">
                Manajemen Stok
            </h1>
        </div>

        <a href="{{ route('stok.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-[#0B1F5B] px-5 py-3 text-sm font-semibold text-white hover:bg-[#091846] transition">
            ⚙ Atur Penyesuaian Stok
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

        <div class="bg-white border rounded-xl p-5">
            <p class="text-xs text-slate-500 font-medium">
                Total Stok Tersedia
            </p>

            <h3 class="mt-3 text-3xl font-bold text-slate-900">
                {{ number_format($stoks->sum('jumlah_stok'),0,',','.') }}
                <span class="text-lg font-medium text-slate-400">Kg</span>
            </h3>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-xs text-red-500 font-semibold">
                Butuh Tindakan
            </p>

            <h3 class="mt-3 text-3xl font-bold text-slate-900">
                {{ $stoks->where('jumlah_stok','<',1000)->count() }}
            </h3>

            <span class="text-sm text-slate-500">
                Stok Kritis
            </span>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-xs text-slate-500">
                Utilitas Gudang
            </p>

            <h3 class="mt-3 text-3xl font-bold text-slate-900">
                {{ $utilitasGudang }}%
            </h3>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-xs text-green-600 font-semibold">
                +12% bulan ini
            </p>

            <h3 class="mt-3 text-3xl font-bold text-slate-900">
                1.4x
            </h3>

            <span class="text-sm text-slate-500">
                Perputaran Stok
            </span>
        </div>

    </div>

    {{-- Konten --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        {{-- Inventaris --}}
        <div class="xl:col-span-8 bg-white border rounded-xl overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="font-semibold text-lg text-slate-800">
                    Daftar Inventaris
                </h2>
            </div>

            <table class="w-full text-sm">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Komoditas</th>
                        <th class="px-6 py-4 text-left">Gudang</th>
                        <th class="px-6 py-4 text-left">Jumlah Stok</th>
                        <th class="px-6 py-4 text-left">Grade</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($stoks as $stok)

                    @php
                        if ($stok->grade === 'C') {
                            $status = 'BUSUK';
                            $badge = 'bg-rose-100 text-rose-700';
                        } else {
                            $status = 'AMAN';
                            $badge = 'bg-green-100 text-green-700';

                            if($stok->jumlah_stok < 1000){
                                $status = 'HAMPIR HABIS';
                                $badge = 'bg-red-100 text-red-700';
                            }elseif($stok->jumlah_stok < 5000){
                                $status = 'RENDAH';
                                $badge = 'bg-yellow-100 text-yellow-700';
                            }
                        }

                        $persen = min(($stok->jumlah_stok / 15000) * 100,100);
                    @endphp

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                    🥔
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-800">
                                        {{ $stok->jenisKentang->nama_jenis ?? '-' }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4">
                            {{ $stok->gudang->nama_gudang ?? '-' }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="font-semibold">
                                {{ number_format($stok->jumlah_stok,0,',','.') }} Kg
                            </div>

                            <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                                <div
                                    class="bg-green-600 h-2 rounded-full"
                                    style="width: {{ $persen }}%">
                                </div>
                            </div>

                        </td>

                        <td class="px-6 py-4">
                            @if($stok->grade === 'A')
                                Grade A (Bagus)
                            @elseif($stok->grade === 'B')
                                Grade B (Baik)
                            @elseif($stok->grade === 'C')
                                Grade C (Busuk)
                            @else
                                Grade {{ $stok->grade ?? '-' }}
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <a href="{{ route('stok.edit',$stok) }}"
                                   class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200">
                                    Edit
                                </a>

                                <form action="{{ route('stok.destroy',$stok) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus stok ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-500">
                            Tidak ada data inventaris.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- Aktivitas --}}
        <div class="xl:col-span-4">

            <div class="bg-white border rounded-xl">

                <div class="px-5 py-4 border-b">
                    <h2 class="font-semibold text-lg">
                        Aktivitas Stok
                    </h2>
                </div>

                <div class="p-5 space-y-4">

                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="font-semibold text-green-700">
                            Masuk
                        </p>
                        <p class="text-sm text-slate-600">
                            Stok berhasil ditambahkan.
                        </p>
                    </div>

                    <div class="border-l-4 border-red-500 pl-4">
                        <p class="font-semibold text-red-700">
                            Keluar
                        </p>
                        <p class="text-sm text-slate-600">
                            Pengiriman ke pelanggan.
                        </p>
                    </div>

                    <div class="border-l-4 border-yellow-500 pl-4">
                        <p class="font-semibold text-yellow-700">
                            Penyesuaian
                        </p>
                        <p class="text-sm text-slate-600">
                            Koreksi stok gudang.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection