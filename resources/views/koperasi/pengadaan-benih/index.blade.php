@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengadaan Benih (PT Champ -> Koperasi)</h1>
            <p class="text-sm text-slate-500">Catat pembelian benih terstandar dari mitra penyedia (PT Champ).</p>
        </div>
        <a href="{{ route('pengadaan-benih.create') }}" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-2xl font-bold transition-all shadow-lg shadow-purple-600/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Transaksi Baru</span>
        </a>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-blue-800">Alur Data Pengadaan Benih</h3>
                <div class="mt-1 text-sm text-blue-700">
                    <p>Halaman ini mencatat benih yang Anda beli dari <strong>PT Champ (Mitra)</strong>. Data yang Anda tambahkan di sini akan <strong class="text-blue-900">OTOMATIS MASUK (BERTAMBAH)</strong> ke menu <strong>Gudang & Stok</strong> Anda sebagai stok benih yang siap didistribusikan ke Petani.</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider rounded-l-2xl">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Komoditas / Varietas</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mitra (PT Champ)</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Volume (Kg)</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total Nilai</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider rounded-r-2xl text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transaksis as $t)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-4 text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}</td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-bold text-slate-800">{{ $t->jenisKentang->nama_jenis ?? '-' }}</span><br>
                            <span class="text-xs text-slate-400 capitalize">{{ $t->jenisKentang->kategori ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-4 text-sm font-medium text-slate-600">{{ $t->mitra->name ?? '-' }}</td>
                        <td class="py-4 px-4 text-sm font-bold text-slate-800 text-right">{{ number_format($t->jumlah_kg, 2, ',', '.') }} Kg</td>
                        <td class="py-4 px-4 text-sm font-bold text-purple-700 text-right">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 text-center">
                            @if($t->status === 'lunas')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-bold uppercase tracking-wide">Lunas</span>
                            @else
                                <span class="px-2.5 py-1 bg-orange-100 text-orange-800 rounded-lg text-xs font-bold uppercase tracking-wide">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                @if($t->status === 'belum lunas')
                                    <form action="{{ route('pengadaan-benih.bayar', $t->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pelunasan transaksi ini?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold transition-colors">Bayar / Lunasi</button>
                                    </form>
                                @endif
                                <form action="{{ route('pengadaan-benih.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">Belum ada catatan transaksi untuk kategori ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transaksis->links() }}
        </div>
    </div>
</div>
@endsection
