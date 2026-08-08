@extends('layouts.app')

@section('content')
<div class="space-y-6 md:space-y-8 print:m-0 print:p-0">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 print:hidden">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-bold uppercase tracking-wider mb-2 border">
                Laporan Mitra
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Pembelian Kentang</h1>
            <p class="text-sm text-slate-500 mt-1">Laporan pembelian kentang Mitra dari Koperasi.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 hover:text-emerald-600 transition shadow-sm">
                Cetak PDF
            </button>
            <a href="{{ route('mitra.laporan.pembelian.export', ['month' => $month, 'year' => $year]) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-800 text-white font-semibold hover:bg-emerald-900 transition shadow-lg shadow-emerald-900/20">
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6 print:hidden">
        <form action="{{ route('mitra.laporan.pembelian') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                <select name="month" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-emerald-600 focus:ring-emerald-600 cursor-pointer outline-none">
                    @foreach(['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'] as $num => $name)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun</label>
                <select name="year" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-emerald-600 focus:ring-emerald-600 cursor-pointer outline-none">
                    @for($y = date('Y') - 2; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">Filter</button>
        </form>
    </div>

    <!-- Print Header -->
    <div class="hidden print:block text-center mb-8 border-b-2 border-slate-800 pb-4">
        <h1 class="text-2xl font-bold uppercase text-slate-900">Laporan Pembelian Kentang Mitra</h1>
        <p class="text-slate-600">Periode: {{ $month }} - {{ $year }}</p>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Koperasi Penjual</th>
                        <th class="px-6 py-4">Varietas</th>
                        <th class="px-6 py-4 text-right">Volume (Kg)</th>
                        <th class="px-6 py-4 text-right">Total Harga</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">🏢 {{ $item->koperasi->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $item->jenisKentang->nama_jenis ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-mono font-bold">{{ number_format($item->jumlah_kg, 2, ',', '.') }} Kg</td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-emerald-800">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-bold uppercase">{{ $item->status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">Tidak ada data laporan pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($data->hasPages())
    <div class="mt-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm print:hidden">
        {{ $data->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .print\:m-0 { margin: 0 !important; }
        .print\:p-0 { padding: 0 !important; }
        .print\:hidden { display: none !important; }
        .print\:block { display: block !important; visibility: visible; }
        .space-y-6 > *, .space-y-8 > * { visibility: visible; }
        table, table * { visibility: visible; }
    }
</style>
@endsection
