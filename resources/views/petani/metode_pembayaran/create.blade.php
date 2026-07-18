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
                <label for="kategori" class="text-sm font-bold text-slate-700">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" id="kategori" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all" required>
                    <option value="Transfer Bank" {{ old('kategori') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="E-Wallet" {{ old('kategori') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet (OVO, Gopay, Dana, dll)</option>
                    <option value="QRIS" {{ old('kategori') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <!-- Provider/Bank Input -->
            <div class="space-y-2">
                <label for="bank" class="text-sm font-bold text-slate-700">Provider / Nama Bank <span class="text-red-500">*</span></label>
                <input type="text" name="bank" id="bank" value="{{ old('bank') }}" placeholder="Contoh: BRI, Gopay, OVO" 
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all" required>
            </div>

            <!-- Atas Nama Input -->
            <div class="space-y-2">
                <label for="atas_nama" class="text-sm font-bold text-slate-700">Atas Nama (Pilih Petani) <span class="text-red-500">*</span></label>
                <select name="atas_nama" id="atas_nama" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all select2" required>
                    <option value="">-- Pilih Petani --</option>
                    @foreach($petanis as $petani)
                        <option value="{{ $petani->name }}" {{ old('atas_nama') == $petani->name ? 'selected' : '' }}>
                            {{ $petani->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- No. Rekening Input -->
            <div class="space-y-2">
                <label for="no_rekening" class="text-sm font-bold text-slate-700">Nomor Akun / Rekening <span class="text-red-500">*</span></label>
                <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening') }}" placeholder="Contoh: 08123456789 / 12345678" 
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all" required>
            </div>

            <!-- QRIS Upload -->
            <div class="space-y-2">
                <label for="qr_image" class="text-sm font-bold text-slate-700">Upload QR Code (Khusus QRIS)</label>
                <input type="file" name="qr_image" id="qr_image" accept="image/*"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-slate-500">Format: JPG, JPEG, PNG (Maks 2MB)</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('metode-pembayaran.index') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-[#001842] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
                Simpan
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
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important;
        font-size: 0.875rem !important;
        line-height: normal !important;
    }
    .select2-search__field {
        outline: none !important;
        font-size: 0.875rem !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
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
    });
</script>
@endpush
