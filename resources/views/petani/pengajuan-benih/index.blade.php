@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-teal-900 via-emerald-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-500/30 text-teal-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                <span>Manajemen Petani</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Pengajuan Benih</h1>
            <p class="text-teal-100/80 text-sm max-w-xl">Ajukan permintaan benih ke Koperasi.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('pengajuan-benih.create') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-teal-600/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Buat Pengajuan
            </a>
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
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">Disetujui</span>
                        @elseif($p->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase tracking-wider">Ditolak</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-sm font-medium">Belum ada pengajuan benih.</p>
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
