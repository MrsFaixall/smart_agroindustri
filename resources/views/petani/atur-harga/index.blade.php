@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-700">Manajemen Harga</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Manajemen Harga</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-500">Monitor harga komoditas kentang dan perbarui harga acuan secara real-time.</p>
        </div>
        <a href="{{ route('atur-harga.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#001842] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-950/15 transition hover:bg-[#002a70]">
            <span class="text-lg leading-none">+</span> Input Harga Baru
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-heroicon-o-check-circle class="h-5 w-5" /> {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border-t-4 border-blue-950 bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Rata-rata Harga Hari Ini</p>
            <p class="mt-3 text-2xl font-bold text-slate-900">Rp {{ number_format($summary['rata_rata'], 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-400">Rata-rata seluruh komoditas</p>
        </div>
        <div class="rounded-2xl border-t-4 border-amber-400 bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Harga Tertinggi</p>
            <p class="mt-3 text-2xl font-bold text-slate-900">Rp {{ number_format($summary['tertinggi']->harga ?? 0, 0, ',', '.') }}</p>
            <p class="mt-2 truncate text-xs text-slate-500">{{ $summary['tertinggi']->jenisKentang->nama_jenis ?? 'Belum ada data' }}</p>
        </div>
        <div class="rounded-2xl border-t-4 border-emerald-700 bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Harga Terendah</p>
            <p class="mt-3 text-2xl font-bold text-slate-900">Rp {{ number_format($summary['terendah']->harga ?? 0, 0, ',', '.') }}</p>
            <p class="mt-2 truncate text-xs text-slate-500">{{ $summary['terendah']->jenisKentang->nama_jenis ?? 'Belum ada data' }}</p>
        </div>
        <div class="rounded-2xl border-t-4 border-slate-400 bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Komoditas Dipantau</p>
            <p class="mt-3 text-2xl font-bold text-slate-900">{{ $summary['total'] }} <span class="text-sm font-medium text-slate-400">Varian</span></p>
            <p class="mt-2 text-xs text-slate-400">Terakhir diperbarui hari ini</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-5">
        <div class="space-y-6 xl:col-span-3">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div><h2 class="font-bold text-slate-900">Daftar Harga Komoditas Saat Ini</h2><p class="mt-1 text-xs text-slate-500">Harga acuan per kilogram.</p></div>
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-600">{{ $summary['total'] }} KOMODITAS</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Komoditas</th><th class="px-5 py-3">Harga/Kg</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($prices as $price)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4"><div class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-amber-100 text-amber-700"><x-heroicon-o-archive-box class="h-5 w-5" /></span><div><p class="font-bold text-slate-800">{{ $price->jenisKentang->nama_jenis ?? '-' }}</p><p class="text-xs text-slate-500">{{ $price->jenisKentang->kualitas ?? 'Komoditas kentang' }}</p></div></div></td>
                                <td class="px-5 py-4 font-mono text-base font-bold text-[#001842]">Rp {{ number_format($price->harga, 0, ',', '.') }}</td>
                                <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('atur-harga.edit', $price) }}" class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100">Set Harga</a><form action="{{ route('atur-harga.destroy', $price) }}" method="POST" onsubmit="return confirm(@js('Hapus harga '.($price->jenisKentang->nama_jenis ?? '').'?'))">@csrf @method('DELETE')<button class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 hover:bg-rose-100">Hapus</button></form></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-16 text-center"><x-heroicon-o-tag class="mx-auto h-10 w-10 text-slate-200" /><p class="mt-3 text-sm text-slate-500">Belum ada harga komoditas.</p><a href="{{ route('atur-harga.create') }}" class="mt-3 inline-block text-sm font-bold text-blue-700">Input harga pertama</a></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><div><h2 class="font-bold text-slate-900">Perbandingan Harga Komoditas</h2><p class="mt-1 text-xs text-slate-500">Perbandingan harga acuan saat ini.</p></div><span class="rounded-full bg-blue-950 px-3 py-1 text-[10px] font-bold text-white">SAAT INI</span></div>
                <div class="mt-5 h-64"><canvas id="hargaChart"></canvas></div>
            </div>
        </div>

        <aside class="flex min-h-[400px] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="border-b border-slate-200 p-5"><h2 class="font-bold text-slate-900">Aktivitas Harga Terkini</h2><p class="mt-1 text-xs text-slate-500">Harga yang terakhir diperbarui.</p></div>
            <div class="flex-1 divide-y divide-slate-100">
                @forelse($prices->take(6) as $price)
                    <div class="flex gap-3 p-5"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-blue-950 text-xs font-bold text-white">{{ strtoupper(substr($price->jenisKentang->nama_jenis ?? 'K', 0, 2)) }}</span><div class="min-w-0 flex-1"><div class="flex justify-between gap-2"><p class="truncate text-sm font-bold text-slate-800">{{ $price->jenisKentang->nama_jenis ?? '-' }}</p><time class="shrink-0 text-[10px] text-slate-400">{{ $price->updated_at?->diffForHumans() }}</time></div><p class="mt-1 text-xs text-slate-500">Harga diperbarui menjadi <span class="font-bold text-emerald-700">Rp {{ number_format($price->harga, 0, ',', '.') }}</span></p></div></div>
                @empty
                    <p class="p-8 text-center text-sm text-slate-400">Belum ada aktivitas harga.</p>
                @endforelse
            </div>
            <div class="border-t border-slate-200 p-4 text-center text-xs text-slate-500">Aktivitas berdasarkan pembaruan harga terakhir.</div>
        </aside>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chart = document.getElementById('hargaChart');
    if (!chart) return;
    new Chart(chart, { type: 'bar', data: { labels: @json($prices->pluck('jenisKentang.nama_jenis')), datasets: [{ label: 'Harga / Kg', data: @json($prices->pluck('harga')), backgroundColor: '#001842', borderRadius: 7, maxBarThickness: 48 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: context => 'Rp ' + context.raw.toLocaleString('id-ID') } } }, scales: { x: { grid: { display: false }, ticks: { color: '#64748b' } }, y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#64748b', callback: value => 'Rp ' + value.toLocaleString('id-ID') } } } } });
});
</script>
@endsection
