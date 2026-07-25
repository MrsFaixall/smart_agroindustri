@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        display: flex; align-items: center; padding-left: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('penjualan-buah.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Catat Penjualan Hasil Panen (Koperasi -> Pasar/PT Champ)</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl shadow-sm text-blue-700 text-sm">
        <strong>💡 Info Penjualan Panen:</strong> Penjualan panen dari form ini akan <strong>mengurangi</strong> timbunan stok hasil panen Koperasi yang dibeli dari Petani.
    </div>

    <form action="{{ route('penjualan-buah.store') }}" method="POST" class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 p-8">
        @csrf
        <div class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pembeli (Mitra / Konsumen) <span class="text-rose-500">*</span></label>
                    <select name="pembeli_id" class="w-full select2" required>
                        <option value="">Pilih Pembeli</option>
                        @foreach($pembelis as $pembeli)
                            <option value="{{ $pembeli->id }}">{{ $pembeli->name }} ({{ ucfirst($pembeli->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Komoditas / Varietas Kentang <span class="text-rose-500">*</span></label>
                    <select name="jenis_kentang_id" class="w-full select2" id="jenis_kentang_select" required>
                        <option value="">Pilih Varietas</option>
                        @foreach($jenisKentangs as $jk)
                            @php 
                                $sisa = $stokTersedia[$jk->id] ?? 0;
                            @endphp
                            <option value="{{ $jk->id }}" data-sisa="{{ $sisa }}">
                                {{ $jk->nama_jenis }} (Stok: {{ number_format($sisa, 2, ',', '.') }} Kg)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Volume / Jumlah (Kg) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="jumlah_kg" id="jumlah_kg" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold outline-none focus:border-purple-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-purple-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Total Harga Transaksi (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="total_harga" id="total_harga" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-purple-700 outline-none focus:border-purple-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-bold outline-none focus:border-purple-500" required>
                        <option value="lunas">Lunas</option>
                        <option value="belum lunas">Belum Lunas</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-purple-600/30">Simpan Transaksi</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
@endsection
