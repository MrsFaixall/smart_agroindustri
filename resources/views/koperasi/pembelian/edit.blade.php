@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
        background-color: #ffffff !important;
        transition: all 0.2s ease;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #9333ea !important;
        box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.2) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        line-height: normal !important;
    }
    .select2-dropdown {
        border: 1px solid #9333ea !important;
        border-radius: 1rem !important;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 9999 !important;
    }
    .select2-search--dropdown {
        padding: 8px !important;
    }
    .select2-search__field {
        outline: none !important;
        font-size: 0.875rem !important;
        border-radius: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        padding: 8px 12px !important;
    }
    .select2-search__field:focus {
        border-color: #9333ea !important;
        box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2) !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #9333ea !important;
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('pembelian.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Transaksi Pembelian</h1>
            <p class="text-xs text-slate-400">Perbarui rincian transaksi pengadaan kentang.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 p-8 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-purple-600 to-indigo-600 absolute top-0 left-0"></div>
        <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengepul / Koperasi (Pembeli) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pengepul / Koperasi (Pembeli) <span class="text-rose-500">*</span></label>
                    <select name="koperasi_id" id="koperasi_select" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                        <option value="">Pilih Koperasi / Pengepul</option>
                        @foreach($koperasis as $koperasi)
                            <option value="{{ $koperasi->id }}" {{ old('koperasi_id', $pembelian->koperasi_id) == $koperasi->id ? 'selected' : '' }}>🏢 {{ $koperasi->name }}</option>
                        @endforeach
                    </select>
                    @error('koperasi_id')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penjual / Pemasok (Petani / Konsumen) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Penjual / Pemasok (Petani / Konsumen) <span class="text-rose-500">*</span></label>
                    <select name="petani_id" id="petani_select" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                        <option value="">Pilih Penjual (Petani / Konsumen)</option>
                        @foreach($petanis as $petani)
                            <option value="{{ $petani->id }}" {{ old('petani_id', $pembelian->petani_id) == $petani->id ? 'selected' : '' }}>
                                @if($petani->role === 'konsumen') 🛒 @else 🌾 @endif {{ $petani->name }} ({{ ucfirst($petani->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('petani_id')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Kentang -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kentang <span class="text-rose-500">*</span></label>
                    <select name="jenis_kentang_id" id="jenis_kentang_select" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                        <option value="">Pilih Jenis Kentang</option>
                        @foreach($jenisKentangs as $jenis)
                            <option value="{{ $jenis->id }}" data-harga="{{ $jenis->harga->harga ?? 0 }}" {{ old('jenis_kentang_id', $pembelian->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_kentang_id')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pembelian -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', $pembelian->tanggal_pembelian) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none" required>
                    @error('tanggal_pembelian')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jumlah (Kg) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Pembelian (Kg) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="jumlah_kg" id="jumlah_kg" value="{{ old('jumlah_kg', $pembelian->jumlah_kg) }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none" required>
                    @error('jumlah_kg')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Per Kg -->
                @php 
                    $hargaAwal = ($pembelian->jumlah_kg > 0) ? ($pembelian->total_harga / $pembelian->jumlah_kg) : 0; 
                @endphp
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Per Kg (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="harga_per_kg" id="harga_per_kg" value="{{ old('harga_per_kg', $hargaAwal) }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none" required>
                    @error('harga_per_kg')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Total Harga Display -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Estimasi Nilai Pembelian</span>
                    <span id="total_harga_display" class="text-2xl font-extrabold text-purple-900 font-mono">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</span>
                </div>
                <input type="hidden" name="total_harga" id="total_harga_input" value="{{ old('total_harga', $pembelian->total_harga) }}">
            </div>

            <!-- Status Pembayaran & Metode Pembayaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="status" id="status_select" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none font-semibold" required>
                        <option value="belum lunas" {{ old('status', $pembelian->status) == 'belum lunas' ? 'selected' : '' }}>⏳ Belum Lunas</option>
                        <option value="lunas" {{ old('status', $pembelian->status) == 'lunas' ? 'selected' : '' }}>✓ Lunas</option>
                    </select>
                </div>

                @php $lastPembayaran = $pembelian->pembayarans->last(); @endphp
                <div id="metode_pembayaran_container">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Metode Pembayaran (Rekening Tujuan)</label>
                    <select name="metode_pembayaran_id" id="metode_pembayaran_select" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2">
                        <option value="">Pilih Rekening Pembayaran</option>
                        @foreach($metodePembayarans as $metode)
                            <option value="{{ $metode->id }}" {{ ($lastPembayaran->metode_pembayaran_id ?? null) == $metode->id ? 'selected' : '' }}>{{ $metode->bank }} - {{ $metode->no_rekening }} (a.n {{ $metode->atas_nama }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('pembelian.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-purple-600/30 transition-all">Perbarui Pembelian</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahKgInput = document.getElementById('jumlah_kg');
        const hargaPerKgInput = document.getElementById('harga_per_kg');
        const totalHargaDisplay = document.getElementById('total_harga_display');
        const totalHargaInput = document.getElementById('total_harga_input');

        function calculateTotal() {
            const kg = parseFloat(jumlahKgInput.value) || 0;
            const harga = parseFloat(hargaPerKgInput.value) || 0;
            const total = kg * harga;

            totalHargaDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            totalHargaInput.value = total;
        }

        jumlahKgInput.addEventListener('input', calculateTotal);
        hargaPerKgInput.addEventListener('input', calculateTotal);

        if (typeof $ !== 'undefined') {
            $('.select2').select2({ width: '100%' });

            $('#jenis_kentang_select').on('change', function() {
                const harga = $(this).find(':selected').data('harga');
                if (harga) {
                    $('#harga_per_kg').val(harga);
                    calculateTotal();
                }
            });
        }
    });
</script>
@endsection
