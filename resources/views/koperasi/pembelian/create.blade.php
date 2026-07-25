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
            <h1 class="text-2xl font-bold text-slate-800">Catat Pembelian Baru</h1>
            <p class="text-xs text-slate-400">Catat pengadaan kentang berdasarkan alokasi Stok Siap Dijual dari Petani/Konsumen.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-4 text-rose-700 shadow-sm">
            <div class="flex items-center gap-2 mb-2 font-bold text-sm">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Ada kendala dalam pencatatan pembelian:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 p-8 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-purple-600 to-indigo-600 absolute top-0 left-0"></div>
        <form action="{{ route('pembelian.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengepul / Koperasi (Pembeli) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pengepul / Koperasi (Pembeli) <span class="text-rose-500">*</span></label>
                    <select name="koperasi_id" id="koperasi_select" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                        <option value="">Pilih Koperasi / Pengepul</option>
                        @foreach($koperasis as $koperasi)
                            <option value="{{ $koperasi->id }}" {{ auth()->id() == $koperasi->id ? 'selected' : '' }}>🏢 {{ $koperasi->name }}</option>
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
                            <option value="{{ $petani->id }}" {{ old('petani_id') == $petani->id ? 'selected' : '' }}>
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
                            <option value="{{ $jenis->id }}" 
                                    data-harga="{{ $jenis->harga->harga ?? 0 }}"
                                    data-stok-dijual="{{ $jenis->stok_siap_dijual }}"
                                    data-stok-fisik="{{ $jenis->total_stok_fisik }}">
                                {{ $jenis->nama_jenis }} — (Stok Siap Dijual: {{ number_format($jenis->stok_siap_dijual, 0, ',', '.') }} Kg)
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_kentang_id')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pembelian -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none" required>
                    @error('tanggal_pembelian')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Panel Indikator Stok Siap Dijual Realtime -->
            <div id="stok_status_card" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5 transition-all">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700 flex items-center gap-1.5">
                        <span>📦 Alokasi Stok Siap Dijual (Manajemen Stok):</span>
                    </span>
                    <span id="stok_siap_badge" class="font-bold font-mono text-slate-700 bg-slate-200/80 px-2.5 py-0.5 rounded-lg text-xs">Pilih Komoditas</span>
                </div>
                <p id="stok_status_detail" class="text-xs text-slate-500 italic">Silakan pilih jenis kentang untuk memeriksa ketersediaan stok siap dijual.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jumlah (Kg) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Pembelian (Kg) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="jumlah_kg" id="jumlah_kg" value="{{ old('jumlah_kg') }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none" required>
                    @error('jumlah_kg')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Per Kg -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Per Kg (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="harga_per_kg" id="harga_per_kg" value="{{ old('harga_per_kg') }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none" required>
                    @error('harga_per_kg')
                        <p class="mt-1 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Total Harga Display -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Estimasi Nilai Pembelian</span>
                    <span id="total_harga_display" class="text-2xl font-extrabold text-purple-900 font-mono">Rp 0</span>
                </div>
                <input type="hidden" name="total_harga" id="total_harga_input" value="{{ old('total_harga', 0) }}">
            </div>

            <!-- Status Pembayaran & Metode Pembayaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="status" id="status_select" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all outline-none font-semibold" required>
                        <option value="belum lunas" {{ old('status') == 'belum lunas' ? 'selected' : '' }}>⏳ Belum Lunas</option>
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>✓ Lunas Langsung</option>
                    </select>
                </div>

                <div id="metode_pembayaran_container">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Metode Pembayaran (Rekening Tujuan)</label>
                    <select name="metode_pembayaran_id" id="metode_pembayaran_select" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2">
                        <option value="">Pilih Rekening Pembayaran</option>
                        @foreach($metodePembayarans as $metode)
                            <option value="{{ $metode->id }}">{{ $metode->bank }} - {{ $metode->no_rekening }} (a.n {{ $metode->atas_nama }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('pembelian.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" id="btn_submit_pembelian" class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-purple-600/30 transition-all">Simpan Transaksi Pembelian</button>
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

        const stokStatusCard = document.getElementById('stok_status_card');
        const stokSiapBadge = document.getElementById('stok_siap_badge');
        const stokStatusDetail = document.getElementById('stok_status_detail');
        const btnSubmit = document.getElementById('btn_submit_pembelian');

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
                const selected = $(this).find(':selected');
                const harga = selected.data('harga');
                const stokDijual = parseFloat(selected.data('stok-dijual')) || 0;
                const stokFisik = parseFloat(selected.data('stok-fisik')) || 0;

                if (harga) {
                    $('#harga_per_kg').val(harga);
                    calculateTotal();
                }

                if ($(this).val()) {
                    if (stokDijual > 0) {
                        stokStatusCard.className = 'p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-1.5 transition-all';
                        stokSiapBadge.className = 'font-bold font-mono text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-lg text-xs';
                        stokSiapBadge.textContent = stokDijual.toLocaleString('id-ID') + ' Kg Tersedia';
                        stokStatusDetail.className = 'text-xs text-emerald-700 font-semibold';
                        stokStatusDetail.textContent = '✓ Stok komoditas siap dijual telah disiapkan di Manajemen Stok. Anda dapat melakukan pengadaan.';
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        stokStatusCard.className = 'p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-1.5 transition-all';
                        stokSiapBadge.className = 'font-bold font-mono text-rose-800 bg-rose-100 px-2.5 py-0.5 rounded-lg text-xs';
                        stokSiapBadge.textContent = '0 Kg (Belum Diatur)';
                        stokStatusDetail.className = 'text-xs text-rose-700 font-bold';
                        stokStatusDetail.textContent = '⚠️ PERHATIAN: Komoditas ini belum diatur untuk dijual (Stok Siap Dijual = 0 Kg). Penjual/Petani harus mengatur alokasi di Manajemen Stok terlebih dahulu.';
                    }
                } else {
                    stokStatusCard.className = 'p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5 transition-all';
                    stokSiapBadge.className = 'font-bold font-mono text-slate-700 bg-slate-200/80 px-2.5 py-0.5 rounded-lg text-xs';
                    stokSiapBadge.textContent = 'Pilih Komoditas';
                    stokStatusDetail.className = 'text-xs text-slate-500 italic';
                    stokStatusDetail.textContent = 'Silakan pilih jenis kentang untuk memeriksa ketersediaan stok siap dijual.';
                }
            });
        }
    });
</script>
@endsection
