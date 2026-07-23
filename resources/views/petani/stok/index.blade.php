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

    {{-- Konten Inventaris --}}
    <div class="w-full bg-white border rounded-xl overflow-hidden shadow-sm">

        <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50/50">
            <div>
                <h2 class="font-bold text-lg text-slate-800">
                    Daftar Inventaris (Stok Terkonsolidasi Per Gudang)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Total akumulasi ketersediaan komoditas dari tiap gudang penyimpanan.</p>
            </div>
        </div>

        <table class="w-full text-sm text-left">

            <thead class="bg-slate-50/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Komoditas</th>
                    <th class="px-6 py-4">Gudang</th>
                    <th class="px-6 py-4">Stok Siap Dijual (Koperasi)</th>
                    <th class="px-6 py-4">Stok Masih di Gudang</th>
                    <th class="px-6 py-4">Grade</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

            @forelse($stoks as $stok)

                @php
                    $dijual = $stok->stok_dijual ?? $stok->jumlah_stok;
                    $tersimpan = max(0, $stok->jumlah_stok - $dijual);

                    if ($stok->grade === 'C') {
                        $status = 'BUSUK / AFKIR';
                        $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                    } elseif ($stok->jumlah_stok <= 0) {
                        $status = 'HABIS';
                        $badge = 'bg-slate-100 text-slate-600 border-slate-200';
                    } elseif ($dijual > 0) {
                        $status = 'SIAP DIJUAL';
                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200 font-bold';
                    } else {
                        $status = 'CADANGAN GUDANG';
                        $badge = 'bg-blue-50 text-blue-700 border-blue-200 font-bold';
                    }

                    $maxGudang = $stok->gudang->kapasitas_max ?? 15000;
                    $persen = $maxGudang > 0 ? min(($stok->jumlah_stok / $maxGudang) * 100, 100) : 0;
                @endphp

                <tr class="hover:bg-slate-50/60 transition-colors">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-lg font-bold">
                                🥔
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">
                                    {{ $stok->jenisKentang->nama_jenis ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 font-medium text-slate-700">
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold">
                            🏢 {{ $stok->gudang->nama_gudang ?? '-' }}
                        </span>
                    </td>

                    <!-- Stok Siap Dijual -->
                    <td class="px-6 py-4">
                        <div class="font-bold text-emerald-700 font-mono text-base flex items-center gap-1.5">
                            <span>🛒</span> {{ number_format($dijual, 0, ',', '.') }} Kg
                        </div>
                        <span class="text-[10px] text-slate-400">Siap dibeli Koperasi</span>
                    </td>

                    <!-- Stok Masih di Gudang -->
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-700 font-mono text-base flex items-center gap-1.5">
                            <span>📦</span> {{ number_format($tersimpan, 0, ',', '.') }} Kg
                        </div>
                        <span class="text-[10px] text-slate-400">Cadangan fisik gudang</span>
                    </td>

                    <td class="px-6 py-4">
                        @if($stok->grade === 'A')
                            <span class="text-xs font-semibold text-slate-700">Grade A (Bagus)</span>
                        @elseif($stok->grade === 'B')
                            <span class="text-xs font-semibold text-slate-700">Grade B (Baik)</span>
                        @elseif($stok->grade === 'C')
                            <span class="text-xs font-semibold text-rose-600">Grade C (Busuk)</span>
                        @else
                            <span class="text-xs font-semibold text-slate-700">Grade {{ $stok->grade ?? '-' }}</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs border font-bold {{ $badge }}">
                            {{ $status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('stok.edit', $stok->id) }}"
                               class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs transition">
                                Edit
                            </a>

                            <form action="{{ route('stok.destroy', $stok->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus stok ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-bold text-xs transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center py-12 text-slate-500">
                        Tidak ada data inventaris di gudang.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>
    </div>

    @include('partials.pagination', ['paginator' => $stoks, 'label' => 'inventaris stok'])

    <!-- Log Aktivitas Pergerakan Stok (Penambahan & Pengurangan) -->
    <div class="w-full bg-white border rounded-xl overflow-hidden shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between border-b pb-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                    <span>⚡</span> Riwayat Aktivitas Pergerakan Stok
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Catatan aktivitas riil ketika stok bertambah (Hasil Panen Baru) atau berkurang (Dibeli oleh Koperasi).</p>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($aktivitasStoks as $log)
                <div class="py-3.5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 hover:bg-slate-50/50 px-2 rounded-xl transition-colors">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $log->type === 'masuk' ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }}">
                            {{ $log->icon }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-bold text-sm text-slate-800">{{ $log->title }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $log->badge }}">
                                    {{ $log->badge_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5">{{ $log->description }}</p>
                        </div>
                    </div>
                    <div class="text-right self-end md:self-center shrink-0">
                        <span class="font-mono font-bold text-base {{ $log->type === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $log->sign }} {{ number_format($log->jumlah_kg, 0, ',', '.') }} Kg
                        </span>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y - H:i') }} WIB
                        </p>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-sm">
                    Belum ada riwayat aktivitas pergerakan stok.
                </div>
            @endforelse
        </div>

        @include('partials.pagination', ['paginator' => $aktivitasStoks])
    </div>

</div>
@endsection