@extends('layouts.app')

@section('content')
<div class="space-y-8">
        <x-petani-page-header 
        title="Riwayat Pengajuan Benih" 
        subtitle="Rekap seluruh riwayat pengajuan benih yang pernah Anda ajukan ke Koperasi."
        icon="sparkles"
        color="teal"
    />

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-5 rounded-3xl shadow-sm">
            <p class="text-emerald-800 text-[11px] font-bold tracking-wider uppercase mb-1">Disetujui</p>
            <h3 class="text-2xl font-extrabold text-emerald-700">{{ $pengajuans->where('status', 'disetujui')->count() }}</h3>
        </div>
        <div class="bg-gradient-to-br from-amber-50/80 via-white to-yellow-50/40 border border-amber-100 p-5 rounded-3xl shadow-sm">
            <p class="text-amber-800 text-[11px] font-bold tracking-wider uppercase mb-1">Pending</p>
            <h3 class="text-2xl font-extrabold text-amber-700">{{ $pengajuans->where('status', 'pending')->count() }}</h3>
        </div>
        <div class="bg-gradient-to-br from-rose-50/80 via-white to-red-50/40 border border-rose-100 p-5 rounded-3xl shadow-sm">
            <p class="text-rose-800 text-[11px] font-bold tracking-wider uppercase mb-1">Ditolak</p>
            <h3 class="flex items-center gap-2 text-2xl font-extrabold text-rose-700">{{ $pengajuans->where('status', 'ditolak')->count() }}</h3>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
    <x-petani-table-filter placeholder="Cari data riwayat pengajuan benih..." />

            <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Tanggal Pengajuan</th>
                    <th class="px-6 py-4">Koperasi Tujuan</th>
                    <th class="px-6 py-4">Jenis Benih</th>
                    <th class="px-6 py-4 text-center">Tipe</th>
                    <th class="px-6 py-4 text-right">Jumlah (Kg)</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengajuans as $p)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">🏢 {{ $p->koperasi->name ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $p->jenisKentang->nama_jenis ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($p->tipe_pengajuan === 'meminta')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold uppercase tracking-wider">Meminta</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-bold uppercase tracking-wider">Membeli</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-extrabold text-slate-800 text-right font-mono">{{ number_format($p->jumlah_kg, 2, ',', '.') }} Kg</td>
                    <td class="px-6 py-4 text-center">
                        @if($p->status === 'disetujui')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">✔ Disetujui</span>
                        @elseif($p->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase tracking-wider">✖ Ditolak</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">⏳ Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada riwayat pengajuan benih.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengajuans->isNotEmpty())
        @include('partials.pagination', ['paginator' => $pengajuans, 'label' => 'riwayat pengajuan'])
    @endif
</div>
@endsection
