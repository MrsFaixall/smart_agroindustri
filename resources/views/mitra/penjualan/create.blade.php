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
        border-color: #059669 !important;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2) !important;
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
        border: 1px solid #059669 !important;
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
        border-color: #059669 !important;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2) !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #059669 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('mitra.penjualan.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Catat Penjualan Kentang (Mitra -> Retail/Konsumen)</h1>
            <p class="text-xs text-slate-400">Penjualan stok komoditas kentang dari Gudang Mitra ke Retail atau Konsumen.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm text-emerald-700 text-sm">
        <strong>💡 Info Penjualan Mitra:</strong> Penjualan dari form ini akan otomatis <strong>mengurangi</strong> timbunan stok kentang Anda di Gudang Mitra.
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 p-8 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-600 to-teal-600 absolute top-0 left-0"></div>
        <form action="{{ route('mitra.penjualan.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pembeli (Retail / Konsumen) <span class="text-rose-500">*</span></label>
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
                                {{ $jk->nama_jenis }} (Stok Anda: {{ number_format($sisa, 2, ',', '.') }} Kg)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Panel Indikator Stok Tersedia Realtime -->
            <div id="stok_status_card" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5 transition-all">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700 flex items-center gap-1.5">
                        <span>📦 Stok Mitra Tersedia:</span>
                    </span>
                    <span id="stok_siap_badge" class="font-bold font-mono text-slate-700 bg-slate-200/80 px-2.5 py-0.5 rounded-lg text-xs">Pilih Komoditas</span>
                </div>
                <p id="stok_status_detail" class="text-xs text-slate-500 italic">Silakan pilih jenis kentang untuk memeriksa ketersediaan stok di Gudang Mitra Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Volume / Jumlah (Kg) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="jumlah_kg" id="jumlah_kg" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Per Kg (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" id="harga_per_kg" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Grade Kualitas <span class="text-rose-500">*</span></label>
                    <select name="grade" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-semibold outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" required>
                        <option value="">Pilih Grade</option>
                        <option value="Grade A">Grade A (Premium)</option>
                        <option value="Grade B">Grade B (Standar Olahan)</option>
                        <option value="Grade C">Grade C (Ekonomi)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-semibold outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" required>
                        <option value="belum lunas">⏳ Belum Lunas</option>
                        <option value="lunas">✓ Lunas</option>
                    </select>
                </div>
            </div>

            <!-- Total Harga Display -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Estimasi Nilai Transaksi</span>
                    <span id="total_harga_display" class="text-2xl font-extrabold text-emerald-800 font-mono">Rp 0</span>
                </div>
                <input type="hidden" name="total_harga" id="total_harga_input" value="0">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('mitra.penjualan.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" id="btn_submit_penjualan" class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg shadow-emerald-600/30">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        if (typeof $ !== 'undefined') {
            $('.select2').select2({ width: '100%' });
        }

        const jumlahKgInput = document.getElementById('jumlah_kg');
        const hargaPerKgInput = document.getElementById('harga_per_kg');
        const totalHargaDisplay = document.getElementById('total_harga_display');
        const totalHargaInput = document.getElementById('total_harga_input');

        const stokStatusCard = document.getElementById('stok_status_card');
        const stokSiapBadge = document.getElementById('stok_siap_badge');
        const stokStatusDetail = document.getElementById('stok_status_detail');
        const btnSubmit = document.getElementById('btn_submit_penjualan');

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
            $('#jenis_kentang_select').on('change', function() {
                const selected = $(this).find(':selected');
                const sisa = parseFloat(selected.data('sisa')) || 0;

                if ($(this).val()) {
                    if (sisa > 0) {
                        stokStatusCard.className = 'p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-1.5 transition-all';
                        stokSiapBadge.className = 'font-bold font-mono text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-lg text-xs';
                        stokSiapBadge.textContent = sisa.toLocaleString('id-ID') + ' Kg Tersedia';
                        stokStatusDetail.className = 'text-xs text-emerald-700 font-semibold';
                        stokStatusDetail.textContent = '✓ Stok Kentang Gudang Mitra mencukupi. Anda dapat melakukan penjualan.';
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        stokStatusCard.className = 'p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-1.5 transition-all';
                        stokSiapBadge.className = 'font-bold font-mono text-rose-800 bg-rose-100 px-2.5 py-0.5 rounded-lg text-xs';
                        stokSiapBadge.textContent = '0 Kg (Habis)';
                        stokStatusDetail.className = 'text-xs text-rose-700 font-bold';
                        stokStatusDetail.textContent = '⚠️ PERHATIAN: Stok kentang di Gudang Mitra Anda kosong. Anda harus membeli kentang dari Koperasi terlebih dahulu.';
                    }
                } else {
                    stokStatusCard.className = 'p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5 transition-all';
                    stokSiapBadge.className = 'font-bold font-mono text-slate-700 bg-slate-200/80 px-2.5 py-0.5 rounded-lg text-xs';
                    stokSiapBadge.textContent = 'Pilih Komoditas';
                    stokStatusDetail.className = 'text-xs text-slate-500 italic';
                    stokStatusDetail.textContent = 'Silakan pilih jenis kentang untuk memeriksa ketersediaan stok di Gudang Mitra Anda.';
                }
            });
        }
    });
</script>
@endpush
@endsection
