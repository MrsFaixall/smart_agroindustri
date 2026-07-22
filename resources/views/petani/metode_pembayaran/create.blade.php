@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="space-y-1">
        <h1 class="text-2xl font-bold text-slate-900">Tambah Metode Pembayaran</h1>
        <p class="text-slate-500 text-sm">Tambahkan rekening baru, e-wallet, atau QRIS untuk menerima pembayaran transaksi.</p>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 shadow-sm">
            <div class="flex items-center gap-2 mb-2 font-semibold text-sm">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Ada beberapa kesalahan pengisian form:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 pl-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <form action="{{ route('metode-pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl p-8 shadow-sm border border-slate-200 border-t-4 border-t-[#001842] space-y-6">
        @csrf

        <div class="grid gap-6 md:grid-cols-2">
            <!-- Kategori -->
            <div class="space-y-2 md:col-span-2">
                <label for="kategori" class="text-sm font-bold text-slate-700">Kategori Pembayaran <span class="text-red-500">*</span></label>
                <select name="kategori" id="kategori" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#001842] focus:ring-1 focus:ring-[#001842] focus:outline-none transition-all" required>
                    <option value="Transfer Bank" {{ old('kategori') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank (BCA, BRI, Mandiri, BNI, BSI, dll)</option>
                    <option value="E-Wallet" {{ old('kategori') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet (GoPay, OVO, DANA, ShopeePay, LinkAja)</option>
                    <option value="QRIS" {{ old('kategori') == 'QRIS' ? 'selected' : '' }}>QRIS (Kode QR Standar)</option>
                    <option value="Virtual Account" {{ old('kategori') == 'Virtual Account' ? 'selected' : '' }}>Virtual Account (BCA VA, Mandiri VA, BRI VA, dll)</option>
                    <option value="Tunai / Cash" {{ old('kategori') == 'Tunai / Cash' ? 'selected' : '' }}>Tunai / Cash (Pembayaran Langsung)</option>
                    <option value="Kartu Kredit / Debit" {{ old('kategori') == 'Kartu Kredit / Debit' ? 'selected' : '' }}>Kartu Kredit / Debit</option>
                </select>
            </div>

            <!-- Provider/Bank Select2 -->
            <div class="space-y-2">
                <label for="bank" class="text-sm font-bold text-slate-700">Provider / Nama Bank <span class="text-red-500">*</span></label>
                <select name="bank" id="bank" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#001842] focus:ring-1 focus:ring-[#001842] focus:outline-none transition-all select2-searchable" required>
                    <option value="">-- Cari / Pilih Bank / E-Wallet --</option>
                    <optgroup label="Bank Konvensional & Syariah">
                        <option value="BCA" data-max="10" {{ old('bank') == 'BCA' ? 'selected' : '' }}>BCA (Bank Central Asia) — 10 Digit</option>
                        <option value="BRI" data-max="15" {{ old('bank') == 'BRI' ? 'selected' : '' }}>BRI (Bank Rakyat Indonesia) — 15 Digit</option>
                        <option value="Bank Mandiri" data-max="13" {{ old('bank') == 'Bank Mandiri' ? 'selected' : '' }}>Bank Mandiri — 13 Digit</option>
                        <option value="BNI" data-max="10" {{ old('bank') == 'BNI' ? 'selected' : '' }}>BNI (Bank Negara Indonesia) — 10 Digit</option>
                        <option value="BSI" data-max="10" {{ old('bank') == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia) — 10 Digit</option>
                        <option value="CIMB Niaga" data-max="14" {{ old('bank') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga — 14 Digit</option>
                        <option value="Bank Danamon" data-max="10" {{ old('bank') == 'Bank Danamon' ? 'selected' : '' }}>Bank Danamon — 10 Digit</option>
                        <option value="Bank Permata" data-max="10" {{ old('bank') == 'Bank Permata' ? 'selected' : '' }}>Bank Permata — 10 Digit</option>
                        <option value="BTN" data-max="16" {{ old('bank') == 'BTN' ? 'selected' : '' }}>BTN (Bank Tabungan Negara) — 16 Digit</option>
                        <option value="Bank Panin" data-max="10" {{ old('bank') == 'Bank Panin' ? 'selected' : '' }}>Bank Panin — 10 Digit</option>
                        <option value="Bank Mega" data-max="10" {{ old('bank') == 'Bank Mega' ? 'selected' : '' }}>Bank Mega — 10 Digit</option>
                        <option value="Bank OCBC NISP" data-max="12" {{ old('bank') == 'Bank OCBC NISP' ? 'selected' : '' }}>Bank OCBC NISP — 12 Digit</option>
                        <option value="Bank Sinarmas" data-max="10" {{ old('bank') == 'Bank Sinarmas' ? 'selected' : '' }}>Bank Sinarmas — 10 Digit</option>
                        <option value="Bank DKI" data-max="10" {{ old('bank') == 'Bank DKI' ? 'selected' : '' }}>Bank DKI — 10 Digit</option>
                        <option value="Bank Jateng" data-max="10" {{ old('bank') == 'Bank Jateng' ? 'selected' : '' }}>Bank Jateng — 10 Digit</option>
                        <option value="Bank Jatim" data-max="10" {{ old('bank') == 'Bank Jatim' ? 'selected' : '' }}>Bank Jatim — 10 Digit</option>
                        <option value="Bank BJB" data-max="10" {{ old('bank') == 'Bank BJB' ? 'selected' : '' }}>Bank BJB (Jabar Banten) — 10 Digit</option>
                    </optgroup>
                    <optgroup label="Bank Digital">
                        <option value="SeaBank" data-max="12" {{ old('bank') == 'SeaBank' ? 'selected' : '' }}>SeaBank — 12 Digit</option>
                        <option value="Bank Jago" data-max="12" {{ old('bank') == 'Bank Jago' ? 'selected' : '' }}>Bank Jago — 12 Digit</option>
                        <option value="Blu by BCA" data-max="10" {{ old('bank') == 'Blu by BCA' ? 'selected' : '' }}>Blu by BCA Digital — 10 Digit</option>
                        <option value="Bank Neo Commerce (Neobank)" data-max="10" {{ old('bank') == 'Bank Neo Commerce (Neobank)' ? 'selected' : '' }}>Bank Neo Commerce (Neobank) — 10 Digit</option>
                        <option value="Jenius (BTPN)" data-max="10" {{ old('bank') == 'Jenius (BTPN)' ? 'selected' : '' }}>Jenius (Bank BTPN) — 10 Digit</option>
                        <option value="Allo Bank" data-max="12" {{ old('bank') == 'Allo Bank' ? 'selected' : '' }}>Allo Bank — 12 Digit</option>
                        <option value="Superbank" data-max="12" {{ old('bank') == 'Superbank' ? 'selected' : '' }}>Superbank — 12 Digit</option>
                        <option value="Line Bank" data-max="12" {{ old('bank') == 'Line Bank' ? 'selected' : '' }}>Line Bank (KBank) — 12 Digit</option>
                        <option value="Krom Bank" data-max="12" {{ old('bank') == 'Krom Bank' ? 'selected' : '' }}>Krom Bank — 12 Digit</option>
                        <option value="MotionBank" data-max="12" {{ old('bank') == 'MotionBank' ? 'selected' : '' }}>MotionBank (MNC Bank) — 12 Digit</option>
                        <option value="Digibank (DBS)" data-max="10" {{ old('bank') == 'Digibank (DBS)' ? 'selected' : '' }}>Digibank (DBS) — 10 Digit</option>
                    </optgroup>
                    <optgroup label="E-Wallet">
                        <option value="GoPay" data-max="13" {{ old('bank') == 'GoPay' ? 'selected' : '' }}>GoPay (Nomor HP) — 10-13 Digit</option>
                        <option value="OVO" data-max="13" {{ old('bank') == 'OVO' ? 'selected' : '' }}>OVO (Nomor HP) — 10-13 Digit</option>
                        <option value="DANA" data-max="13" {{ old('bank') == 'DANA' ? 'selected' : '' }}>DANA (Nomor HP) — 10-13 Digit</option>
                        <option value="ShopeePay" data-max="13" {{ old('bank') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay (Nomor HP) — 10-13 Digit</option>
                        <option value="LinkAja" data-max="13" {{ old('bank') == 'LinkAja' ? 'selected' : '' }}>LinkAja (Nomor HP) — 10-13 Digit</option>
                        <option value="Sakuku" data-max="13" {{ old('bank') == 'Sakuku' ? 'selected' : '' }}>Sakuku (BCA) — 10-13 Digit</option>
                    </optgroup>
                    <optgroup label="Virtual Account">
                        <option value="BCA Virtual Account" data-max="18" {{ old('bank') == 'BCA Virtual Account' ? 'selected' : '' }}>BCA Virtual Account — Max 18 Digit</option>
                        <option value="BRI Virtual Account" data-max="18" {{ old('bank') == 'BRI Virtual Account' ? 'selected' : '' }}>BRI Virtual Account — Max 18 Digit</option>
                        <option value="Mandiri Virtual Account" data-max="18" {{ old('bank') == 'Mandiri Virtual Account' ? 'selected' : '' }}>Mandiri Virtual Account — Max 18 Digit</option>
                        <option value="BNI Virtual Account" data-max="18" {{ old('bank') == 'BNI Virtual Account' ? 'selected' : '' }}>BNI Virtual Account — Max 18 Digit</option>
                        <option value="Permata Virtual Account" data-max="18" {{ old('bank') == 'Permata Virtual Account' ? 'selected' : '' }}>Permata Virtual Account — Max 18 Digit</option>
                    </optgroup>
                    <optgroup label="QRIS & Lainnya">
                        <option value="QRIS Standar" data-max="18" {{ old('bank') == 'QRIS Standar' ? 'selected' : '' }}>QRIS Standar</option>
                        <option value="Tunai Direct" data-max="18" {{ old('bank') == 'Tunai Direct' ? 'selected' : '' }}>Tunai / Cash Direct</option>
                    </optgroup>
                </select>
                <p class="text-xs text-slate-500">Cari dan pilih bank/provider dari daftar di atas.</p>
            </div>

            <!-- Atas Nama Input -->
            <div class="space-y-2">
                <label for="atas_nama" class="text-sm font-bold text-slate-700">Atas Nama (Pilih Petani) <span class="text-red-500">*</span></label>
                <select name="atas_nama" id="atas_nama" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#001842] focus:ring-1 focus:ring-[#001842] focus:outline-none transition-all select2" required>
                    <option value="">-- Pilih Petani --</option>
                    @foreach($petanis as $petani)
                        <option value="{{ $petani->name }}" {{ old('atas_nama') == $petani->name ? 'selected' : '' }}>
                            {{ $petani->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- No. Rekening Input & Digit Helper -->
            <div class="space-y-2 md:col-span-2">
                <div class="flex items-center justify-between">
                    <label for="no_rekening" class="text-sm font-bold text-slate-700">Nomor Akun / Rekening <span class="text-red-500">*</span></label>
                    <span id="digit_counter" class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-mono border border-slate-200">0 / 10 Digit</span>
                </div>
                <input type="text" name="no_rekening" id="no_rekening" inputmode="numeric" maxlength="10" value="{{ old('no_rekening') }}" placeholder="Masukkan nomor rekening (Contoh: 1234567890)" 
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-mono focus:border-[#001842] focus:ring-1 focus:ring-[#001842] focus:outline-none transition-all" required>
                
                <!-- Helper Guide Card -->
                <div id="digit_guide_box" class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1.5 text-slate-600">
                    <div class="font-semibold text-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#001842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Status Digit Rekening:</span>
                        </div>
                        <span id="target_bank_badge" class="font-mono text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200 font-bold">BCA (Max 10 Digit)</span>
                    </div>
                    <p id="digit_status_text" class="text-[11px] text-slate-500 pt-0.5 font-medium italic">Silakan pilih bank dan masukkan angka nomor rekening.</p>
                </div>
            </div>

            <!-- QRIS Upload -->
            <div class="space-y-2 md:col-span-2">
                <label for="qr_image" class="text-sm font-bold text-slate-700">Upload QR Code (Khusus QRIS / Digital)</label>
                <input type="file" name="qr_image" id="qr_image" accept="image/*"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[#001842] focus:ring-1 focus:ring-[#001842] focus:outline-none transition-all file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-slate-500">Format gambar: JPG, JPEG, PNG (Maksimal 2MB)</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('metode-pembayaran.index') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-[#001842] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
                Simpan Metode Pembayaran
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #001842 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important;
        font-size: 0.875rem !important;
        line-height: normal !important;
    }
    .select2-dropdown {
        border: 1px solid #001842 !important;
        border-radius: 0.5rem !important;
        overflow: hidden;
    }
    .select2-search__field {
        outline: none !important;
        font-size: 0.875rem !important;
    }
    .select2-search__field:focus {
        border-color: #001842 !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #001842 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: '-- Pilih Petani --'
        });

        $('.select2-searchable').select2({
            width: '100%',
            placeholder: '-- Cari / Pilih Bank / E-Wallet --'
        });

        const bankSelect = $('#bank');
        const noRekeningInput = document.getElementById('no_rekening');
        const digitCounter = document.getElementById('digit_counter');
        const digitStatusText = document.getElementById('digit_status_text');
        const targetBankBadge = document.getElementById('target_bank_badge');

        function updateBankDigitLimit() {
            const selectedOpt = bankSelect.find(':selected');
            const bankName = bankSelect.val() || 'Bank';
            let maxDigit = selectedOpt.data('max') || 18;

            noRekeningInput.setAttribute('maxlength', maxDigit);

            // Trim if currently exceeds new maxDigit
            if (noRekeningInput.value.length > maxDigit) {
                noRekeningInput.value = noRekeningInput.value.slice(0, maxDigit);
            }

            targetBankBadge.textContent = `${bankName} (Max ${maxDigit} Digit)`;
            validateDigits(maxDigit, bankName);
        }

        function validateDigits(maxDigitOverride, bankNameOverride) {
            noRekeningInput.value = noRekeningInput.value.replace(/[^0-9]/g, '');
            const selectedOpt = bankSelect.find(':selected');
            const bankName = bankNameOverride || bankSelect.val() || 'Bank';
            const maxDigit = maxDigitOverride || selectedOpt.data('max') || 18;

            const len = noRekeningInput.value.length;

            digitCounter.textContent = `${len} / ${maxDigit} Digit`;

            if (len === 0) {
                digitCounter.className = 'text-xs font-semibold px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-mono border border-slate-200';
                digitStatusText.textContent = `Silakan masukkan nomor rekening ${bankName} (Maksimal ${maxDigit} digit).`;
                digitStatusText.className = 'text-[11px] text-slate-500 pt-0.5 font-medium italic';
                return;
            }

            if (len === parseInt(maxDigit)) {
                digitCounter.className = 'text-xs font-semibold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 font-mono border border-emerald-300';
                digitStatusText.textContent = `✓ Nomor rekening telah pas ${len} digit sesuai batas maksimal ${bankName}.`;
                digitStatusText.className = 'text-[11px] text-emerald-600 font-semibold pt-0.5';
            } else {
                digitCounter.className = 'text-xs font-semibold px-2.5 py-1 rounded bg-amber-100 text-amber-800 font-mono border border-amber-300';
                digitStatusText.textContent = `ℹ️ Nomor rekening ${bankName} saat ini ${len} dari ${maxDigit} digit.`;
                digitStatusText.className = 'text-[11px] text-amber-700 font-medium pt-0.5';
            }
        }

        bankSelect.on('change', function() {
            updateBankDigitLimit();
        });

        if (noRekeningInput) {
            noRekeningInput.addEventListener('input', function() {
                const selectedOpt = bankSelect.find(':selected');
                const maxDigit = selectedOpt.data('max') || 18;
                const bankName = bankSelect.val() || 'Bank';
                validateDigits(maxDigit, bankName);
            });
        }

        updateBankDigitLimit();
    });
</script>
@endpush
