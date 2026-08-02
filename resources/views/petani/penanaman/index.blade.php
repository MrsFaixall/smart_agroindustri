@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Monitoring Penanaman Benih</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau benih yang sedang ditanam hingga memasuki masa panen.</p>
        </div>
        <a href="{{ route('penanaman.create') }}" class="inline-flex items-center gap-2 bg-[#001842] hover:bg-blue-900 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-900/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tanam Benih Baru
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 font-semibold text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-100 font-semibold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($penanamans as $tanam)
            @php
                $startDate = \Carbon\Carbon::parse($tanam->tanggal_tanam);
                $endDate = \Carbon\Carbon::parse($tanam->estimasi_panen);
                $now = now();
                
                $totalDays = $startDate->diffInDays($endDate);
                $daysPassed = $startDate->diffInDays($now, false); // false for negative if future
                $daysLeft = $now->diffInDays($endDate, false);
                
                if ($daysPassed < 0) {
                    $progress = 0;
                } elseif ($daysPassed >= $totalDays) {
                    $progress = 100;
                } else {
                    $progress = ($daysPassed / $totalDays) * 100;
                }

                $statusBg = 'bg-blue-50 text-blue-700 border-blue-200';
                if ($tanam->status === 'selesai') $statusBg = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                if ($tanam->status === 'gagal') $statusBg = 'bg-rose-50 text-rose-700 border-rose-200';
                if ($progress >= 100 && $tanam->status === 'aktif') $statusBg = 'bg-amber-50 text-amber-700 border-amber-200';
            @endphp
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-100/50 border border-slate-100 relative overflow-hidden group hover:border-blue-200 transition-colors">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-100">
                    <div class="h-full {{ $tanam->status === 'selesai' ? 'bg-emerald-500' : 'bg-blue-500' }}" style="width: {{ $progress }}%"></div>
                </div>
                
                <div class="flex justify-between items-start mb-4 mt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-bold text-xl border border-green-100">
                            🌱
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $tanam->jenisKentang->nama_jenis }}</h3>
                            <p class="text-xs font-semibold text-slate-500">🏢 {{ $tanam->gudang->nama_gudang }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border uppercase tracking-wider {{ $statusBg }}">
                        {{ $tanam->status }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Jumlah Ditanam</span>
                        <span class="font-bold text-slate-800">{{ number_format($tanam->jumlah_tanam_kg, 0, ',', '.') }} Kg</span>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <div class="flex justify-between text-[11px] font-semibold text-slate-500 mb-1">
                            <span>Mulai: {{ $startDate->translatedFormat('d M Y') }}</span>
                            <span>Panen: {{ $endDate->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 mb-1">
                            <div class="{{ $progress >= 100 ? 'bg-amber-500' : 'bg-blue-500' }} h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-[10px] text-center font-bold {{ $progress >= 100 ? 'text-amber-600' : 'text-blue-600' }}">
                            @if($tanam->status === 'selesai')
                                Sudah Dipanen
                            @elseif($progress >= 100)
                                Siap Dipanen! (Melewati {{ abs($daysLeft) }} hari)
                            @else
                                Sisa {{ $daysLeft }} Hari
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        @if($tanam->status === 'aktif')
                            <form action="{{ route('penanaman.destroy', $tanam->id) }}" method="POST" onsubmit="return confirm('Batalkan penanaman dan kembalikan bibit ke gudang?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 underline">Batal Tanam</button>
                            </form>
                            
                            @if($progress >= 90) <!-- Bisa panen jika sisa 10 hari lagi -->
                                <a href="{{ route('panen.create', ['penanaman_id' => $tanam->id]) }}" class="bg-amber-400 hover:bg-amber-500 text-amber-900 px-4 py-2 rounded-lg text-xs font-bold shadow-md transition-colors">
                                    Proses Panen 🚜
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400 font-semibold italic">Belum masa panen</span>
                            @endif
                        @else
                            <div class="w-full text-center">
                                <a href="{{ route('panen.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline">Lihat Hasil Panen</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Penanaman</h3>
                <p class="text-slate-500 text-sm mb-6 max-w-md mx-auto">Anda belum menanam bibit apapun. Silakan menanam bibit dari stok gudang Anda untuk memulai siklus pertanian.</p>
                <a href="{{ route('penanaman.create') }}" class="inline-flex items-center gap-2 bg-[#001842] hover:bg-blue-900 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                    Mulai Tanam Bibit
                </a>
            </div>
        @endforelse
    </div>
    
    @if($penanamans->hasPages())
        <div class="mt-8">
            {{ $penanamans->links() }}
        </div>
    @endif
</div>
@endsection
