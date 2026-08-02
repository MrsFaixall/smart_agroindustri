@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                <span>Manajemen Koperasi</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Persetujuan Pengajuan Benih</h1>
            <p class="text-blue-100/80 text-sm max-w-xl">Tinjau dan setujui pengajuan benih dari Petani.</p>
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
                    <th class="px-6 py-4">Tanggal Pengajuan</th>
                    <th class="px-6 py-4">Petani Pengaju</th>
                    <th class="px-6 py-4">Jenis Benih</th>
                    <th class="px-6 py-4 text-center">Tipe</th>
                    <th class="px-6 py-4 text-right">Jumlah (Kg)</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengajuans as $p)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">👤 {{ $p->petani->name ?? '-' }}</td>
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
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">Disetujui</span>
                        @elseif($p->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase tracking-wider">Ditolak</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if($p->status === 'pending')
                            <form action="{{ route('pengajuan-benih.approve', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">Setuju</button>
                            </form>
                            <form action="{{ route('pengajuan-benih.reject', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs transition-all shadow-sm">Tolak</button>
                            </form>
                            @else
                            <span class="text-xs text-slate-400 font-medium">Selesai</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada pengajuan benih masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengajuans->isNotEmpty())
        @include('partials.pagination', ['paginator' => $pengajuans, 'label' => 'pengajuan'])
    @endif
</div>
@endsection
