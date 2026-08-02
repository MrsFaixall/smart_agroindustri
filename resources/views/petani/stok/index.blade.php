@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-950 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>Inventaris & Stok Terkonsolidasi</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen Stok</h1>
            <p class="text-indigo-100/80 text-sm max-w-xl">Monitor akumulasi stok komoditas dan alokasi siap jual ke Koperasi.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('stok.create') }}"
               class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-indigo-600/30 transform hover:-translate-y-0.5">
                ⚙ Atur Penyesuaian Stok
            </a>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-blue-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-blue-800 text-[11px] font-bold tracking-wider uppercase">Total Stok Tersedia</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20">
                        <x-heroicon-o-cube class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    {{ number_format($stoks->sum('jumlah_stok'),0,',','.') }}
                    <span class="text-sm font-semibold text-slate-400">Kg</span>
                </h3>
            </div>
            <p class="mt-3 text-xs text-blue-700 font-medium">Akumulasi seluruh gudang</p>
        </div>

        <div class="bg-gradient-to-br from-rose-50/80 via-white to-orange-50/40 border border-rose-200/80 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-rose-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-rose-600 text-[11px] font-bold tracking-wider uppercase">Butuh Tindakan</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-md shadow-rose-500/20">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-rose-600 tracking-tight">
                    {{ $stoks->where('jumlah_stok','<',1000)->count() }} <span class="text-sm font-semibold text-rose-400">Varian</span>
                </h3>
            </div>
            <p class="mt-3 text-xs text-rose-700 font-semibold">Stok Kritis (&lt; 1.000 Kg)</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50/80 via-white to-indigo-50/40 border border-purple-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-purple-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-purple-800 text-[11px] font-bold tracking-wider uppercase">Utilitas Gudang</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-md shadow-purple-500/20">
                        <x-heroicon-o-chart-pie class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    {{ $utilitasGudang }}%
                </h3>
            </div>
            <p class="mt-3 text-xs text-purple-700 font-medium">Kapasitas terpakai</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
            <div class="bg-emerald-500/10 absolute -right-6 -bottom-6 w-24 h-24 rounded-full blur-xl group-hover:scale-150 transition-all duration-500 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-emerald-800 text-[11px] font-bold tracking-wider uppercase">Perputaran Stok</p>
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                        <x-heroicon-o-arrow-path class="w-5 h-5"/>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">
                    1.4x
                </h3>
            </div>
            <p class="mt-3 text-xs text-emerald-700 font-bold">+12% bulan ini</p>
        </div>
    </div>

    <!-- Konten Inventaris -->
    <div class="w-full bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/60">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="font-bold text-lg text-slate-800">
                    Daftar Inventaris (Stok Terkonsolidasi Per Gudang)
                </h2>
                <p class="text-xs text-slate-400">Total akumulasi ketersediaan komoditas dari tiap gudang penyimpanan</p>
            </div>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
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
                    $isBenih = isset($stok->jenisKentang) && $stok->jenisKentang->kategori === 'benih_hulu';
                    $dijual = $isBenih ? 0 : ($stok->stok_dijual ?? $stok->jumlah_stok);
                    $tersimpan = max(0, $stok->jumlah_stok - $dijual);

                    if ($stok->grade === 'C') {
                        $status = 'BUSUK / AFKIR';
                        $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                    } elseif ($stok->jumlah_stok <= 0) {
                        $status = 'HABIS';
                        $badge = 'bg-slate-100 text-slate-600 border-slate-200';
                    } elseif ($isBenih) {
                        $status = 'BENIH SIAP TANAM';
                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200 font-bold';
                    } elseif ($dijual > 0) {
                        $status = 'SIAP DIJUAL';
                        $badge = 'bg-blue-50 text-blue-700 border-blue-200 font-bold';
                    } else {
                        $status = 'CADANGAN GUDANG';
                        $badge = 'bg-amber-50 text-amber-700 border-amber-200 font-bold';
                    }

                    $maxGudang = $stok->gudang->kapasitas_max ?? 15000;
                    $persen = $maxGudang > 0 ? min(($stok->jumlah_stok / $maxGudang) * 100, 100) : 0;
                @endphp

                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-sm flex items-center justify-center text-lg font-bold">
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
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-3 py-1 rounded-xl border border-slate-200 text-xs font-semibold">
                            🏢 {{ $stok->gudang->nama_gudang ?? '-' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-extrabold text-emerald-700 font-mono text-base flex items-center gap-1.5">
                            <span>🛒</span> {{ number_format($dijual, 0, ',', '.') }} Kg
                        </div>
                        <span class="text-[10px] text-slate-400">Siap dibeli Koperasi</span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-700 font-mono text-base flex items-center gap-1.5">
                            <span>📦</span> {{ number_format($tersimpan, 0, ',', '.') }} Kg
                        </div>
                        <span class="text-[10px] text-slate-400">Cadangan fisik gudang</span>
                    </td>

                    <td class="px-6 py-4">
                        @if($stok->grade === 'A')
                            <span class="text-xs font-bold text-emerald-700">Grade A (Bagus)</span>
                        @elseif($stok->grade === 'B')
                            <span class="text-xs font-bold text-blue-700">Grade B (Baik)</span>
                        @elseif($stok->grade === 'C')
                            <span class="text-xs font-bold text-rose-600">Grade C (Busuk)</span>
                        @else
                            <span class="text-xs font-bold text-slate-700">Grade {{ $stok->grade ?? '-' }}</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[11px] border font-bold shadow-2xs {{ $badge }}">
                            {{ $status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            @if($isBenih && $stok->jumlah_stok > 0)
                                <a href="{{ route('penanaman.create') }}" class="rounded-xl bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100 transition-colors">
                                    🌱 Tanam
                                </a>
                            @elseif(!$isBenih && $stok->jumlah_stok > 0)
                                <a href="{{ route('petani.penawaran-panen.create', ['stok_id' => $stok->id]) }}" class="rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-colors">
                                    Tawarkan
                                </a>
                            @endif

                            <a href="{{ route('stok.edit', $stok->id) }}"
                               class="rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                                Edit
                            </a>

                            <form action="{{ route('stok.destroy', $stok->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus stok ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-xl bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-slate-400 font-medium">
                        Tidak ada data inventaris di gudang.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $stoks, 'label' => 'inventaris stok'])

    <!-- Log Aktivitas -->
    <div class="w-full bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/60 p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                    <span>⚡</span> Riwayat Aktivitas Pergerakan Stok
                </h3>
                <p class="text-xs text-slate-400">Catatan aktivitas stok masuk (Panen) & keluar (Transaksi Koperasi)</p>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($aktivitasStoks as $log)
                <div class="py-3.5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 hover:bg-slate-50/60 px-3 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg {{ $log->type === 'masuk' ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }}">
                            {{ $log->icon }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-bold text-sm text-slate-800">{{ $log->title }}</h4>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $log->badge }}">
                                    {{ $log->badge_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $log->description }}</p>
                        </div>
                    </div>
                    <div class="text-right self-end md:self-center shrink-0">
                        <span class="font-mono font-extrabold text-base {{ $log->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $log->sign }} {{ number_format($log->jumlah_kg, 0, ',', '.') }} Kg
                        </span>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">
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