@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-1">
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Penawaran Penjualan</h1>
            <p class="text-blue-100/80 text-sm max-w-xl">Tawarkan hasil panen konsumsi Anda ke Koperasi dan negosiasikan harganya.</p>
        </div>
        <div class="relative z-10">
            <a href="{{ route('petani.penawaran-panen.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5">
                <span class="text-lg leading-none">+</span> Buat Penawaran
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
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Komoditas & Koperasi</th>
                    <th class="px-6 py-4">Jumlah (Kg)</th>
                    <th class="px-6 py-4">Total Harga Anda</th>
                    <th class="px-6 py-4">Total Tawaran Koperasi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
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
                        @php
                            $pasar = $hargaPasars->get($penawaran->jenis_kentang_id);
                        @endphp
                        @if($pasar)
                            <div class="text-[10px] text-slate-500 mt-1">Patokan Koperasi: <span class="font-semibold text-amber-700">Rp {{ number_format($pasar->harga, 0, ',', '.') }}</span></div>
                        @endif
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
                    <td class="px-6 py-4 text-right">
                        @if($penawaran->status === 'dinegosiasi')
                            <button onclick="openModal('modal-{{ $penawaran->id }}')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                Respon Negosiasi
                            </button>

                            <!-- Modal -->
                            <div id="modal-{{ $penawaran->id }}" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl shadow-slate-900/20 transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                                            <div class="bg-white px-6 pb-6 pt-6 text-left">
                                                <h3 class="text-xl font-bold text-slate-800 mb-2">Respon Negosiasi Koperasi</h3>
                                                <p class="text-sm text-slate-500 mb-6">Koperasi menawar secara total sebesar <strong>Rp {{ number_format($penawaran->harga_tawaran_koperasi, 0, ',', '.') }}</strong>.</p>
                                                
                                                <div class="flex justify-between gap-4">
                                                    <!-- Accept Form -->
                                                    <form action="{{ route('petani.penawaran-panen.update', $penawaran->id) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="action" value="accept">
                                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-2xl transition-all shadow-lg shadow-emerald-500/30">
                                                            Sepakat (Jual Sekarang)
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Reject Form -->
                                                    <form action="{{ route('petani.penawaran-panen.update', $penawaran->id) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold py-3 px-4 rounded-2xl transition-all border border-rose-200">
                                                            Tolak
                                                        </button>
                                                    </form>
                                                </div>

                                                @if($penawaran->jumlah_tawar_petani < 2)
                                                    <div class="mt-6 pt-6 border-t border-slate-100">
                                                        <p class="text-xs font-bold text-slate-700 mb-3">Atau Tawar Balik dengan Total Harga Baru:</p>
                                                        <form action="{{ route('petani.penawaran-panen.update', $penawaran->id) }}" method="POST" class="flex gap-3">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="action" value="counter">
                                                            <input type="number" name="harga_tawaran_petani" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" value="{{ $penawaran->harga_tawaran_petani ?? $penawaran->harga_tawaran_koperasi }}" required min="100">
                                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-5 rounded-2xl transition-all shadow-lg shadow-indigo-500/30">
                                                                Tawar
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="mt-6 pt-6 border-t border-slate-100">
                                                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-xs text-center font-bold">
                                                            Anda telah mencapai batas maksimal tawar-menawar (2 kali). Anda hanya bisa Sepakat atau Menolak tawaran terakhir Koperasi.
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="bg-slate-50 px-6 py-4 flex justify-end">
                                                <button type="button" onclick="closeModal('modal-{{ $penawaran->id }}')" class="text-sm font-semibold text-slate-600 hover:text-slate-800">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-[10px] text-slate-400 italic">Tidak ada aksi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data penawaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $penawarans->links() }}
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endsection
