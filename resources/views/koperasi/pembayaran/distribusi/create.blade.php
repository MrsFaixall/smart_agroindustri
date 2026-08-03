@extends('layouts.app')

@push('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Midtrans Snap JS -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransClientKey }}"></script>
<style>
    .payment-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .payment-card:hover {
        transform: translateY(-2px);
    }
    .payment-card.active-payment-card {
        border-color: #10b981 !important; /* emerald-500 */
        background-color: #f0fdf4 !important; /* emerald-50 */
        box-shadow: 0 4px 12px -1px rgba(16, 185, 129, 0.12), 0 2px 4px -1px rgba(16, 185, 129, 0.08) !important;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.25s ease-out forwards;
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pt-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('koperasi.pembayaran.distribusi') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Catat Pembayaran Transaksi</h1>
            <p class="text-xs text-slate-400">Pilih tagihan transaksi pembelian dan selesaikan rincian pembayaran.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-4 text-rose-700 shadow-sm">
            <ul class="list-disc list-inside text-sm space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 overflow-hidden relative">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-600 to-teal-600 absolute top-0 left-0"></div>
        
        <form action="{{ route('koperasi.pembayaran.distribusi.store') }}" method="POST" id="payment-form" class="p-8 space-y-6">
            @csrf

            <!-- Tagihan Summary -->
            <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 rounded-2xl p-6 flex items-center justify-between border border-emerald-100/80 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Pelanggan / Petani</p>
                    <p id="summary-petani" class="font-extrabold text-slate-800 text-base">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Total Tagihan</p>
                    <p id="summary-total" class="text-2xl font-extrabold text-emerald-700 font-mono">Rp 0</p>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Pembelian Select -->
                <div class="space-y-2">
                    <label for="distribusi_benih_id" class="block text-sm font-semibold text-slate-700">Pilih Transaksi Pembelian</label>
                    <select name="distribusi_benih_id" id="distribusi_benih_id" 
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 select2" required>
                        <option value="" disabled selected>-- Pilih Transaksi Pembelian (Tagihan Belum Lunas) --</option>
                        @forelse($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}" data-petani="{{ $transaksi->petani->name ?? 'N/A' }}" data-total="{{ $transaksi->total_harga }}" {{ old('distribusi_benih_id', request('distribusi_benih_id')) == $transaksi->id ? 'selected' : '' }}>
                                TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }} — {{ $transaksi->petani->name ?? 'Petani' }} — Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }} ({{ \Carbon\Carbon::parse($transaksi->tanggal_pembelian)->format('d M Y') }})
                            </option>
                        @empty
                            <option value="" disabled>-- Tidak ada tagihan transaksi yang belum lunas --</option>
                        @endforelse
                    </select>
                </div>

                <!-- Metode Pembayaran Grid -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                    
                    <input type="hidden" name="metode_pembayaran_id" id="metode_pembayaran_id" value="">
                    <input type="hidden" name="midtrans_payment_type" id="midtrans_payment_type" value="">

                    <div class="space-y-4">
                        <!-- Kategori Manual -->
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kas & Transfer Manual</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Tunai Card -->
                                <button type="button" data-method-type="tunai" class="payment-card flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 hover:border-emerald-200 bg-white hover:bg-slate-50 transition-all text-left group">
                                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-105 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-800">Tunai / Kas Koperasi</p>
                                        <p class="text-xs text-slate-400 font-semibold">Bayar langsung dengan kas</p>
                                    </div>
                                </button>

                                <!-- Transfer Manual Card -->
                                <button type="button" data-method-type="manual" class="payment-card flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 hover:border-emerald-200 bg-white hover:bg-slate-50 transition-all text-left group">
                                    <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 group-hover:scale-105 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-800">Transfer Bank Manual</p>
                                        <p class="text-xs text-slate-400 font-semibold">Transfer ke rekening petani</p>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Dropdown Rekening Manual -->
                        <div id="manual-account-select-wrapper" class="hidden space-y-2 p-4 bg-slate-50 rounded-2xl border border-slate-100 animate-fadeIn">
                            <label for="manual_bank_select" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pilih Rekening Tujuan</label>
                            <select id="manual_bank_select" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs bg-white text-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-semibold">
                                <option value="" disabled selected>-- Pilih Rekening Transfer Manual --</option>
                                @foreach($methods as $metode)
                                    <option value="{{ $metode->id }}">
                                        🏢 {{ $metode->kategori }} - {{ $metode->bank }} (A/N {{ $metode->atas_nama }} - {{ $metode->no_rekening }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Accordion Midtrans -->
                        <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                            <!-- Accordion Header -->
                            <button type="button" id="midtrans-accordion-btn" class="w-full flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-200 focus:outline-none">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span id="accordion-title" class="text-sm font-extrabold text-slate-800">Lihat Semua Metode Midtrans</span>
                                        <p id="accordion-subtitle" class="text-[11px] text-slate-400 font-semibold">Virtual Account, QRIS, GoPay, ShopeePay</p>
                                    </div>
                                </div>
                                <div class="text-slate-400 transition-transform duration-200" id="accordion-chevron" style="transition: transform 0.2s;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>

                            <!-- Accordion Content (collapsible) -->
                            <div id="midtrans-accordion-content" class="hidden transition-all duration-300">
                                <div class="p-4 space-y-4 bg-white">
                                    
                                    <!-- QRIS & Dompet Digital Group -->
                                    <div class="space-y-2">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">QRIS & DOMPET DIGITAL</p>
                                        <div class="space-y-2">
                                            <!-- QRIS Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="qris" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-5 w-auto object-contain" alt="QRIS">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">QRIS</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>

                                            <!-- GoPay Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="gopay" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" class="h-4 w-auto object-contain" alt="GoPay">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">GoPay</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>

                                            <!-- ShopeePay Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="shopeepay" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/ShopeePay_Logo.svg" class="h-4 w-auto object-contain" alt="ShopeePay">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">ShopeePay</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Virtual Account / Bank Transfer Group -->
                                    <div class="space-y-2">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">VIRTUAL ACCOUNT / TRANSFER BANK</p>
                                        <div class="space-y-2">
                                            <!-- BCA VA Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="bca_va" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-4 w-auto object-contain" alt="BCA">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">BCA Virtual Account</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>

                                            <!-- BRI VA Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="bri_va" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_Logo.svg" class="h-4 w-auto object-contain" alt="BRI">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">BRI Virtual Account</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>

                                            <!-- Mandiri VA Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="mandiri_bill" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" class="h-3 w-auto object-contain" alt="Mandiri">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">Mandiri Virtual Account</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>

                                            <!-- BNI VA Row -->
                                            <button type="button" data-method-type="midtrans" data-channel="bni_va" class="payment-card w-full flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all text-left">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-8 border border-slate-100 rounded-lg flex items-center justify-center bg-white p-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/01/BNI_logo.svg" class="h-4 w-auto object-contain" alt="BNI">
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">BNI Virtual Account</span>
                                                </div>
                                                <div class="active-indicator hidden text-emerald-600">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nominal Bayar -->
                    <div class="space-y-2">
                        <label for="jumlah_bayar" class="block text-sm font-semibold text-slate-700">Nominal Dibayar (Rp)</label>
                        <input type="number" step="0.01" name="jumlah_bayar" id="jumlah_bayar" value="{{ old('jumlah_bayar') }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 font-mono font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
                    </div>

                    <!-- Tanggal Pembayaran -->
                    <div class="space-y-2">
                        <label for="tanggal_pembayaran" class="block text-sm font-semibold text-slate-700">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="space-y-2">
                    <label for="status" class="block text-sm font-semibold text-slate-700">Status Pelunasan</label>
                    <select name="status" id="status" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-emerald-500 transition-all outline-none font-bold" required>
                        <option value="lunas" selected>✅ Lunas (Selesai)</option>
                        <option value="pending">⏳ Pending (Tertunda)</option>
                    </select>
                </div>

                <!-- Catatan -->
                <div class="space-y-2">
                    <label for="catatan" class="block text-sm font-semibold text-slate-700">Catatan / Keterangan (Opsional)</label>
                    <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan tambahan..." class="w-full rounded-2xl border border-slate-200 p-4 text-sm text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('koperasi.pembayaran.distribusi') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" id="pay-button" class="rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition-all">Simpan & Proses Pembayaran</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pembelianSelect = document.getElementById('distribusi_benih_id');
        const summaryPetani = document.getElementById('summary-petani');
        const summaryTotal = document.getElementById('summary-total');
        const jumlahBayar = document.getElementById('jumlah_bayar');
        const paymentForm = document.getElementById('payment-form');
        const hiddenMethodInput = document.getElementById('metode_pembayaran_id');
        const hiddenMidtransTypeInput = document.getElementById('midtrans_payment_type');
        const manualAccountWrapper = document.getElementById('manual-account-select-wrapper');
        const manualBankSelect = document.getElementById('manual_bank_select');
        const cards = document.querySelectorAll('.payment-card');

        // Accordion elements
        const accordionBtn = document.getElementById('midtrans-accordion-btn');
        const accordionContent = document.getElementById('midtrans-accordion-content');
        const accordionChevron = document.getElementById('accordion-chevron');
        const accordionTitle = document.getElementById('accordion-title');
        const accordionSubtitle = document.getElementById('accordion-subtitle');

        function updateSummary() {
            if (!pembelianSelect) return;
            const selectedOption = pembelianSelect.options[pembelianSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const petani = selectedOption.getAttribute('data-petani');
                const total = parseFloat(selectedOption.getAttribute('data-total')) || 0;
                summaryPetani.textContent = petani;
                summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                jumlahBayar.value = total;
            } else {
                summaryPetani.textContent = '-';
                summaryTotal.textContent = 'Rp 0';
                jumlahBayar.value = '';
            }
        }

        if (pembelianSelect) {
            pembelianSelect.addEventListener('change', updateSummary);
            updateSummary();
        }

        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({ width: '100%' });
            $('#distribusi_benih_id').on('change', updateSummary);
        }

        // Accordion toggle handler
        if (accordionBtn && accordionContent) {
            accordionBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isHidden = accordionContent.classList.contains('hidden');
                if (isHidden) {
                    accordionContent.classList.remove('hidden');
                    if (accordionChevron) accordionChevron.style.transform = 'rotate(180deg)';
                } else {
                    accordionContent.classList.add('hidden');
                    if (accordionChevron) accordionChevron.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Selection logic for cards
        function selectCard(card) {
            // Remove active classes and indicators
            cards.forEach(c => {
                c.classList.remove('active-payment-card');
                const indicator = c.querySelector('.active-indicator');
                if (indicator) indicator.classList.add('hidden');
            });
            
            // Activate selected card
            card.classList.add('active-payment-card');
            const indicator = card.querySelector('.active-indicator');
            if (indicator) indicator.classList.remove('hidden');

            const methodType = card.getAttribute('data-method-type');
            
            if (methodType === 'tunai') {
                hiddenMethodInput.value = '';
                hiddenMidtransTypeInput.value = '';
                if (manualAccountWrapper) manualAccountWrapper.classList.add('hidden');
                if (manualBankSelect) manualBankSelect.removeAttribute('required');
                
                // Reset Accordion Header style & text
                if (accordionTitle) accordionTitle.textContent = 'Lihat Semua Metode Midtrans';
                if (accordionSubtitle) accordionSubtitle.textContent = 'Virtual Account, QRIS, GoPay, ShopeePay';
                if (accordionContent) accordionContent.classList.add('hidden');
                if (accordionChevron) accordionChevron.style.transform = 'rotate(0deg)';
            } else if (methodType === 'manual') {
                if (manualBankSelect) {
                    hiddenMethodInput.value = manualBankSelect.value;
                    manualBankSelect.setAttribute('required', 'required');
                }
                hiddenMidtransTypeInput.value = '';
                if (manualAccountWrapper) manualAccountWrapper.classList.remove('hidden');
                
                // Reset Accordion Header style & text
                if (accordionTitle) accordionTitle.textContent = 'Lihat Semua Metode Midtrans';
                if (accordionSubtitle) accordionSubtitle.textContent = 'Virtual Account, QRIS, GoPay, ShopeePay';
                if (accordionContent) accordionContent.classList.add('hidden');
                if (accordionChevron) accordionChevron.style.transform = 'rotate(0deg)';
            } else if (methodType === 'midtrans') {
                const channel = card.getAttribute('data-channel');
                hiddenMethodInput.value = 'midtrans';
                hiddenMidtransTypeInput.value = channel;
                if (manualAccountWrapper) manualAccountWrapper.classList.add('hidden');
                if (manualBankSelect) manualBankSelect.removeAttribute('required');

                // Update Accordion Header with selection
                const channelName = card.querySelector('span').textContent;
                if (accordionTitle) accordionTitle.textContent = 'Midtrans: ' + channelName;
                if (accordionSubtitle) accordionSubtitle.textContent = 'Metode Instant Terpilih (Klik untuk mengubah)';
            }
        }

        cards.forEach(card => {
            card.addEventListener('click', function() {
                selectCard(this);
            });
        });

        if (manualBankSelect) {
            manualBankSelect.addEventListener('change', function() {
                const activeCard = document.querySelector('.payment-card.active-payment-card');
                if (activeCard && activeCard.getAttribute('data-method-type') === 'manual') {
                    hiddenMethodInput.value = this.value;
                }
            });
        }

        // Initialize state (e.g. on load or after validation fails)
        const initialMethodId = "{{ old('metode_pembayaran_id') }}";
        const initialMidtransType = "{{ old('midtrans_payment_type') }}";
        let initialSelected = false;

        if (initialMethodId === 'midtrans' && initialMidtransType) {
            const card = document.querySelector(`.payment-card[data-channel="${initialMidtransType}"]`);
            if (card) {
                selectCard(card);
                initialSelected = true;
                // If it is midtrans, auto-open accordion so user sees the checkmark
                if (accordionContent) accordionContent.classList.remove('hidden');
                if (accordionChevron) accordionChevron.style.transform = 'rotate(180deg)';
            }
        } else if (initialMethodId && initialMethodId !== 'midtrans') {
            const card = document.querySelector('.payment-card[data-method-type="manual"]');
            if (card) {
                selectCard(card);
                if (manualBankSelect) {
                    manualBankSelect.value = initialMethodId;
                }
                hiddenMethodInput.value = initialMethodId;
                initialSelected = true;
            }
        }

        if (!initialSelected) {
            const defaultCard = document.querySelector('.payment-card[data-method-type="tunai"]');
            if (defaultCard) selectCard(defaultCard);
        }

        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                if (hiddenMethodInput && hiddenMethodInput.value === 'midtrans') {
                    e.preventDefault();

                    const pembelianId = pembelianSelect.value;
                    const amount = jumlahBayar.value;
                    const catatan = document.getElementById('catatan').value;
                    const paymentType = hiddenMidtransTypeInput.value;

                    if (!pembelianId || !amount) {
                        alert('Harap pilih transaksi pembelian dan jumlah bayar yang valid.');
                        return;
                    }

                    const payButton = document.getElementById('pay-button');
                    const originalText = payButton.textContent;
                    payButton.disabled = true;
                    payButton.textContent = 'Menghubungkan ke Midtrans...';

                    fetch('{{ route("midtrans.snap-token") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            distribusi_benih_id: pembelianId,
                            jumlah_bayar: amount,
                            catatan: catatan,
                            payment_type: paymentType
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        payButton.disabled = false;
                        payButton.textContent = originalText;

                        if (typeof snap !== 'undefined') {
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    window.location.href = '{{ route("pembayaran.finish") }}?order_id=' + data.order_id + '&status_code=200&transaction_status=settlement';
                                },
                                onPending: function(result) {
                                    window.location.href = '{{ route("pembayaran.finish") }}?order_id=' + data.order_id + '&status_code=201&transaction_status=pending';
                                },
                                onError: function(result) {
                                    window.location.href = '{{ route("pembayaran.finish") }}?order_id=' + data.order_id + '&status_code=407&transaction_status=error';
                                },
                                onClose: function() {
                                    alert('Pembayaran dibatalkan.');
                                }
                            });
                        } else {
                            alert('Midtrans Snap SDK gagal dimuat. Harap periksa koneksi internet Anda.');
                        }
                    })
                    .catch(error => {
                        payButton.disabled = false;
                        payButton.textContent = originalText;
                        console.error('Error:', error);
                        alert('Gagal mendapatkan token pembayaran Midtrans: ' + (error.error || 'Terjadi kesalahan internal.'));
                    });
                }
            });
        }
    });
</script>
@endsection
