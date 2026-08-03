@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-1">
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Riwayat Penawaran Panen</h1>
            <p class="text-blue-100/80 text-sm max-w-xl">Lihat daftar penawaran hasil panen yang telah disetujui atau ditolak.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 text-rose-700 font-semibold border border-rose-200">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-100/60">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Komoditas & Koperasi</th>
                    <th class="px-6 py-4">Jumlah (Kg)</th>
                    <th class="px-6 py-4">Total Harga Anda</th>
                    <th class="px-6 py-4">Total Tawaran Koperasi</th>
                    <th class="px-6 py-4">Status</th>

                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($penawarans as $penawaran)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-slate-600 font-medium">
                        {{ $penawaran->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $penawaran->jenisKentang->nama_jenis ?? '-' }}</div>
                        <div class="text-[10px] font-bold text-indigo-600 mt-0.5">🏢 {{ $penawaran->koperasi->name ?? '-' }}</div>

                    </td>
                    <td class="px-6 py-4 font-bold text-slate-700 font-mono">
                        {{ number_format($penawaran->jumlah_kg, 0, ',', '.') }} Kg
                    </td>
                    <td class="px-6 py-4 font-bold text-blue-700 font-mono">
                        Rp {{ number_format($penawaran->harga_tawaran_petani, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-emerald-700 font-mono">
                        @if($penawaran->harga_tawaran_koperasi)
                            Rp {{ number_format($penawaran->harga_tawaran_koperasi, 0, ',', '.') }}
                        @else
                            <span class="text-slate-400 font-medium text-xs">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-slate-100 text-slate-700 border-slate-200',
                                'dinegosiasi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ];
                            $color = $statusColors[$penawaran->status] ?? $statusColors['menunggu'];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold border shadow-2xs {{ $color }} uppercase tracking-wider">
                            {{ $penawaran->status }}
                        </span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data penawaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($penawarans->isNotEmpty())
        @include('partials.pagination', ['paginator' => $penawarans, 'label' => 'riwayat penawaran panen'])
    @endif
    
    <div class="mt-4">
        {{ $penawarans->links() }}
    </div>
</div>


@endsection
