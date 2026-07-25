@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('metode-pembayaran.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Metode Pembayaran</h1>
            <p class="text-xs text-slate-400">Perbarui informasi rekening bank konvensional, bank digital, e-wallet, atau QRIS.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-4 text-rose-700 shadow-sm">
            <div class="flex items-center gap-2 mb-2 font-bold text-sm">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Ada beberapa kesalahan pengisian form:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('metode-pembayaran.update', $method->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-blue-600 to-indigo-600 absolute top-0 left-0"></div>
        @csrf
        @method('PUT')

        <div class="grid gap-6 md:grid-cols-2">
            <!-- Kategori Pembayaran -->
            <div class="space-y-2 md:col-span-2">
                <label for="kategori" class="block text-sm font-semibold text-slate-700">Kategori Pembayaran <span class="text-rose-500">*</span></label>
                <select name="kategori" id="kategori" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" required>
                    <option value="Transfer Bank" {{ (old('kategori') ?? $method->kategori) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank (BCA, BRI, Mandiri, BNI, BSI, dll)</option>
                    <option value="E-Wallet" {{ (old('kategori') ?? $method->kategori) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet (GoPay, OVO, DANA, ShopeePay, LinkAja)</option>
                    <option value="QRIS" {{ (old('kategori') ?? $method->kategori) == 'QRIS' ? 'selected' : '' }}>QRIS (Kode QR Standar)</option>
                    <option value="Virtual Account" {{ (old('kategori') ?? $method->kategori) == 'Virtual Account' ? 'selected' : '' }}>Virtual Account (BCA VA, Mandiri VA, BRI VA, dll)</option>
                    <option value="Tunai / Cash" {{ (old('kategori') ?? $method->kategori) == 'Tunai / Cash' ? 'selected' : '' }}>Tunai / Cash (Pembayaran Langsung)</option>
                    <option value="Kartu Kredit / Debit" {{ (old('kategori') ?? $method->kategori) == 'Kartu Kredit / Debit' ? 'selected' : '' }}>Kartu Kredit / Debit</option>
                </select>
            </div>

            <!-- Provider / Nama Bank Select2 Searchable -->
            <div class="space-y-2">
                <label for="bank" class="block text-sm font-semibold text-slate-700">Provider / Nama Bank <span class="text-rose-500">*</span></label>
                @php $currentBank = old('bank', $method->bank); @endphp
                <select name="bank" id="bank" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2-searchable" required>
                    <option value="">-- Cari / Pilih Bank / E-Wallet --</option>
                    <optgroup label="Bank Konvensional & Syariah">
                        <option value="BCA" data-max="10" {{ $currentBank == 'BCA' ? 'selected' : '' }}>BCA (Bank Central Asia) — 10 Digit</option>
                        <option value="BRI" data-max="15" {{ $currentBank == 'BRI' ? 'selected' : '' }}>BRI (Bank Rakyat Indonesia) — 15 Digit</option>
                        <option value="Bank Mandiri" data-max="13" {{ $currentBank == 'Bank Mandiri' ? 'selected' : '' }}>Bank Mandiri — 13 Digit</option>
                        <option value="BNI" data-max="10" {{ $currentBank == 'BNI' ? 'selected' : '' }}>BNI (Bank Negara Indonesia) — 10 Digit</option>
                        <option value="BSI" data-max="10" {{ $currentBank == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia) — 10 Digit</option>
                        <option value="CIMB Niaga" data-max="14" {{ $currentBank == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga — 14 Digit</option>
                        <option value="Bank Danamon" data-max="10" {{ $currentBank == 'Bank Danamon' ? 'selected' : '' }}>Bank Danamon — 10 Digit</option>
                        <option value="Bank Permata" data-max="10" {{ $currentBank == 'Bank Permata' ? 'selected' : '' }}>Bank Permata — 10 Digit</option>
                        <option value="BTN" data-max="16" {{ $currentBank == 'BTN' ? 'selected' : '' }}>BTN (Bank Tabungan Negara) — 16 Digit</option>
                        <option value="Bank Panin" data-max="10" {{ $currentBank == 'Bank Panin' ? 'selected' : '' }}>Bank Panin — 10 Digit</option>
                        <option value="Bank Mega" data-max="10" {{ $currentBank == 'Bank Mega' ? 'selected' : '' }}>Bank Mega — 10 Digit</option>
                        <option value="Bank OCBC NISP" data-max="12" {{ $currentBank == 'Bank OCBC NISP' ? 'selected' : '' }}>Bank OCBC NISP — 12 Digit</option>
                        <option value="Bank Sinarmas" data-max="10" {{ $currentBank == 'Bank Sinarmas' ? 'selected' : '' }}>Bank Sinarmas — 10 Digit</option>
                        <option value="Bank DKI" data-max="10" {{ $currentBank == 'Bank DKI' ? 'selected' : '' }}>Bank DKI — 10 Digit</option>
                        <option value="Bank Jateng" data-max="10" {{ $currentBank == 'Bank Jateng' ? 'selected' : '' }}>Bank Jateng — 10 Digit</option>
                        <option value="Bank Jatim" data-max="10" {{ $currentBank == 'Bank Jatim' ? 'selected' : '' }}>Bank Jatim — 10 Digit</option>
                        <option value="Bank BJB" data-max="10" {{ $currentBank == 'Bank BJB' ? 'selected' : '' }}>Bank BJB (Jabar Banten) — 10 Digit</option>
                    </optgroup>
                    <optgroup label="Bank Digital">
                        <option value="SeaBank" data-max="12" {{ $currentBank == 'SeaBank' ? 'selected' : '' }}>SeaBank — 12 Digit</option>
                        <option value="Bank Jago" data-max="12" {{ $currentBank == 'Bank Jago' ? 'selected' : '' }}>Bank Jago — 12 Digit</option>
                        <option value="Blu by BCA" data-max="10" {{ $currentBank == 'Blu by BCA' ? 'selected' : '' }}>Blu by BCA Digital — 10 Digit</option>
                        <option value="Bank Neo Commerce (Neobank)" data-max="10" {{ $currentBank == 'Bank Neo Commerce (Neobank)' ? 'selected' : '' }}>Bank Neo Commerce (Neobank) — 10 Digit</option>
                        <option value="Jenius (BTPN)" data-max="10" {{ $currentBank == 'Jenius (BTPN)' ? 'selected' : '' }}>Jenius (Bank BTPN) — 10 Digit</option>
                        <option value="Allo Bank" data-max="12" {{ $currentBank == 'Allo Bank' ? 'selected' : '' }}>Allo Bank — 12 Digit</option>
                        <option value="Superbank" data-max="12" {{ $currentBank == 'Superbank' ? 'selected' : '' }}>Superbank — 12 Digit</option>
                        <option value="Line Bank" data-max="12" {{ $currentBank == 'Line Bank' ? 'selected' : '' }}>Line Bank (KBank) — 12 Digit</option>
                        <option value="Krom Bank" data-max="12" {{ $currentBank == 'Krom Bank' ? 'selected' : '' }}>Krom Bank — 12 Digit</option>
                        <option value="MotionBank" data-max="12" {{ $currentBank == 'MotionBank' ? 'selected' : '' }}>MotionBank (MNC Bank) — 12 Digit</option>
                        <option value="Digibank (DBS)" data-max="10" {{ $currentBank == 'Digibank (DBS)' ? 'selected' : '' }}>Digibank (DBS) — 10 Digit</option>
                    </optgroup>
                    <optgroup label="E-Wallet">
                        <option value="GoPay" data-max="13" {{ $currentBank == 'GoPay' ? 'selected' : '' }}>GoPay (Nomor HP) — 10-13 Digit</option>
                        <option value="OVO" data-max="13" {{ $currentBank == 'OVO' ? 'selected' : '' }}>OVO (Nomor HP) — 10-13 Digit</option>
                        <option value="DANA" data-max="13" {{ $currentBank == 'DANA' ? 'selected' : '' }}>DANA (Nomor HP) — 10-13 Digit</option>
                        <option value="ShopeePay" data-max="13" {{ $currentBank == 'ShopeePay' ? 'selected' : '' }}>ShopeePay (Nomor HP) — 10-13 Digit</option>
                        <option value="LinkAja" data-max="13" {{ $currentBank == 'LinkAja' ? 'selected' : '' }}>LinkAja (Nomor HP) — 10-13 Digit</option>
                        <option value="Sakuku" data-max="13" {{ $currentBank == 'Sakuku' ? 'selected' : '' }}>Sakuku (BCA) — 10-13 Digit</option>
                    </optgroup>
                    <optgroup label="Virtual Account">
                        <option value="BCA Virtual Account" data-max="18" {{ $currentBank == 'BCA Virtual Account' ? 'selected' : '' }}>BCA Virtual Account — Max 18 Digit</option>
                        <option value="BRI Virtual Account" data-max="18" {{ $currentBank == 'BRI Virtual Account' ? 'selected' : '' }}>BRI Virtual Account — Max 18 Digit</option>
                        <option value="Mandiri Virtual Account" data-max="18" {{ $currentBank == 'Mandiri Virtual Account' ? 'selected' : '' }}>Mandiri Virtual Account — Max 18 Digit</option>
                        <option value="BNI Virtual Account" data-max="18" {{ $currentBank == 'BNI Virtual Account' ? 'selected' : '' }}>BNI Virtual Account — Max 18 Digit</option>
                        <option value="Permata Virtual Account" data-max="18" {{ $currentBank == 'Permata Virtual Account' ? 'selected' : '' }}>Permata Virtual Account — Max 18 Digit</option>
                    </optgroup>
                    <optgroup label="QRIS & Lainnya">
                        <option value="QRIS Standar" data-max="18" {{ $currentBank == 'QRIS Standar' ? 'selected' : '' }}>QRIS Standar</option>
                        <option value="Tunai Direct" data-max="18" {{ $currentBank == 'Tunai Direct' ? 'selected' : '' }}>Tunai / Cash Direct</option>
                    </optgroup>
                </select>
                <p class="text-xs text-slate-400">Ketik untuk mencari bank konvensional, bank digital, atau e-wallet.</p>
            </div>

            <!-- Atas Nama Input -->
            <div class="space-y-2">
                <label for="atas_nama" class="block text-sm font-semibold text-slate-700">Atas Nama (Pilih Petani) <span class="text-rose-500">*</span></label>
                <select name="atas_nama" id="atas_nama" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">-- Pilih Petani --</option>
                    @foreach($petanis as $petani)
                        <option value="{{ $petani->name }}" {{ old('atas_nama', $method->atas_nama) == $petani->name ? 'selected' : '' }}>
                            {{ $petani->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- No. Rekening Input & Digit Counter -->
            <div class="space-y-2 md:col-span-2">
                <div class="flex items-center justify-between">
                    <label for="no_rekening" class="block text-sm font-semibold text-slate-700">Nomor Akun / Rekening <span class="text-rose-500">*</span></label>
                    <span id="digit_counter" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-mono border border-slate-200">0 / 10 Digit</span>
                </div>
                <input type="text" name="no_rekening" id="no_rekening" inputmode="numeric" maxlength="10" value="{{ old('no_rekening', $method->no_rekening) }}" placeholder="Masukkan nomor rekening" 
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" required>
                
                <!-- Helper Guide Card -->
                <div id="digit_guide_box" class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs space-y-1.5 text-slate-600">
                    <div class="font-bold text-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Status Digit Rekening:</span>
                        </div>
                        <span id="target_bank_badge" class="font-mono text-[11px] bg-blue-50 text-blue-700 px-2.5 py-0.5 rounded-full border border-blue-200 font-bold">Pilih Bank</span>
                    </div>
                    <p id="digit_status_text" class="text-[11px] text-slate-500 pt-0.5 font-medium italic">Silakan pilih bank/provider dan masukkan nomor rekening.</p>
                </div>
            </div>

            <!-- QRIS Upload -->
            <div class="space-y-2 md:col-span-2">
                <label for="qr_image" class="block text-sm font-semibold text-slate-700">Upload QR Code Baru (Khusus QRIS / Digital)</label>
                <input type="file" name="qr_image" id="qr_image" accept="image/*"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-slate-400">Biarkan kosong jika tidak ingin mengubah QR saat ini.</p>

                @if($method->qr_image)
                    <div class="mt-2 flex items-center gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="{{ asset('storage/' . $method->qr_image) }}" alt="QR Code" class="h-16 w-auto rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500 font-medium">QR Code aktif saat ini</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('metode-pembayaran.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

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
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
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
        border: 1px solid #3b82f6 !important;
        border-radius: 1rem !important;
        overflow: hidden;
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

            if (noRekeningInput) {
                noRekeningInput.setAttribute('maxlength', maxDigit);

                if (noRekeningInput.value.length > maxDigit) {
                    noRekeningInput.value = noRekeningInput.value.slice(0, maxDigit);
                }
            }

            if (targetBankBadge) {
                targetBankBadge.textContent = `${bankName} (Max ${maxDigit} Digit)`;
            }
            validateDigits(maxDigit, bankName);
        }

        function validateDigits(maxDigitOverride, bankNameOverride) {
            if (!noRekeningInput) return;
            noRekeningInput.value = noRekeningInput.value.replace(/[^0-9]/g, '');
            const selectedOpt = bankSelect.find(':selected');
            const bankName = bankNameOverride || bankSelect.val() || 'Bank';
            const maxDigit = maxDigitOverride || selectedOpt.data('max') || 18;

            const len = noRekeningInput.value.length;

            if (digitCounter) {
                digitCounter.textContent = `${len} / ${maxDigit} Digit`;
            }

            if (len === 0) {
                if (digitCounter) digitCounter.className = 'text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-mono border border-slate-200';
                if (digitStatusText) {
                    digitStatusText.textContent = `Silakan masukkan nomor rekening ${bankName} (Maksimal ${maxDigit} digit).`;
                    digitStatusText.className = 'text-[11px] text-slate-500 pt-0.5 font-medium italic';
                }
                return;
            }

            if (len === parseInt(maxDigit)) {
                if (digitCounter) digitCounter.className = 'text-xs font-bold px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-mono border border-emerald-300';
                if (digitStatusText) {
                    digitStatusText.textContent = `✓ Nomor rekening telah pas ${len} digit sesuai batas maksimal ${bankName}.`;
                    digitStatusText.className = 'text-[11px] text-emerald-600 font-bold pt-0.5';
                }
            } else {
                if (digitCounter) digitCounter.className = 'text-xs font-bold px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-mono border border-amber-300';
                if (digitStatusText) {
                    digitStatusText.textContent = `ℹ️ Nomor rekening ${bankName} saat ini ${len} dari ${maxDigit} digit.`;
                    digitStatusText.className = 'text-[11px] text-amber-700 font-semibold pt-0.5';
                }
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
