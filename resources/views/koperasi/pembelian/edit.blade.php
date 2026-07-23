@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
        background-color: #ffffff !important;
        transition: all 0.2s ease;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #001842 !important;
        box-shadow: 0 0 0 1px #001842 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 0.875rem !important;
        line-height: normal !important;
    }
    .select2-dropdown {
        border: 1px solid #001842 !important;
        border-radius: 0.75rem !important;
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
        border-radius: 0.5rem !important;
        border: 1px solid #cbd5e1 !important;
        padding: 8px 12px !important;
    }
    .select2-search__field:focus {
        border-color: #001842 !important;
        box-shadow: 0 0 0 1px #001842 !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #001842 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('pembelian.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Pembelian</h1>
            <p class="text-slate-500 text-sm">Ubah data transaksi pembelian kentang.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Koperasi -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Koperasi</label>
                    <select name="koperasi_id" id="koperasi_select" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors select2" required>
                        <option value="">Pilih Koperasi</option>
                        @foreach($koperasis as $koperasi)
                            <option value="{{ $koperasi->id }}" {{ $pembelian->koperasi_id == $koperasi->id ? 'selected' : '' }}>{{ $koperasi->name }}</option>
                        @endforeach
                    </select>
                    @error('koperasi_id')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- Petani -->
                 <div>
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Petani</label>
                     <select name="petani_id" id="petani_select" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors select2" required>
                         <option value="">Pilih Petani</option>
                         @foreach($petanis as $petani)
                             <option value="{{ $petani->id }}" {{ $pembelian->petani_id == $petani->id ? 'selected' : '' }}>{{ $petani->name }}</option>
                         @endforeach
                     </select>
                     @error('petani_id')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
             </div>
 
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <!-- Jenis Kentang -->
                 <div>
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kentang</label>
                     <select name="jenis_kentang_id" id="jenis_kentang_select" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors select2" required>
                         <option value="" data-stok="0" data-gudang="" data-harga="0">Pilih Jenis</option>
                         @foreach($jenisKentangs as $jenis)
                             <option value="{{ $jenis->id }}" data-stok="{{ $jenis->total_stok }}" data-gudang="{{ $jenis->gudang_info }}" data-harga="{{ $jenis->harga_per_kg }}" {{ old('jenis_kentang_id', $pembelian->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>
                                 {{ $jenis->nama_jenis }} (Stok Total: {{ $jenis->total_stok }} Kg)
                             </option>
                         @endforeach
                     </select>
                     <p id="stok_info" class="mt-2 text-xs text-slate-600 font-medium hidden bg-blue-50 border border-blue-100 p-3 rounded-xl">
                         📦 Total Stok: <span id="stok_text" class="text-slate-900 font-bold">0</span> Kg <br>
                         🏢 Lokasi Stok: <span id="gudang_text" class="text-blue-800 font-semibold">-</span> <br>
                         🏷️ Harga: <span id="harga_text" class="text-emerald-600 font-bold">Rp 0</span> / Kg
                     </p>
                     @error('jenis_kentang_id')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
 
                 <!-- Tanggal -->
                 <div>
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pembelian</label>
                     <input type="date" name="tanggal_pembelian" value="{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->format('Y-m-d') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                     @error('tanggal_pembelian')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
             </div>
 
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <!-- Jumlah (Kg) -->
                 <div>
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Beli (Kg)</label>
                     <input type="number" step="0.01" name="jumlah_kg" id="jumlah_kg_input" value="{{ old('jumlah_kg', $pembelian->jumlah_kg) }}" placeholder="Contoh: 1500.5" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                     @error('jumlah_kg')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
 
                 <!-- Total Harga -->
                 <div>
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Total Harga (Rp)</label>
                     <input type="number" name="total_harga" id="total_harga_input" value="{{ old('total_harga', $pembelian->total_harga) }}" placeholder="Contoh: 15000000" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                     <p class="mt-1.5 text-xs text-slate-500">Harga akan terisi otomatis jika jenis kentang dan jumlah beli diisi.</p>
                     @error('total_harga')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
             </div>
 
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran</label>
                     <select name="status" id="status_select" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                         <option value="belum lunas" {{ old('status', $pembelian->status) == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                         <option value="lunas" {{ old('status', $pembelian->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                     </select>
                     @error('status')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
 
                 <!-- Metode Pembayaran Rekening (Conditional) -->
                 <div id="metode_pembayaran_wrapper" class="hidden">
                     <label class="block text-sm font-semibold text-slate-700 mb-2">Metode Pembayaran (Rekening Petani)</label>
                     <select name="metode_pembayaran_id" id="metode_pembayaran_select" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors select2">
                         <option value="">Pilih Rekening Tujuan</option>
                         @foreach($metodePembayarans as $method)
                             <option value="{{ $method->id }}" data-user-id="{{ $method->user_id }}" {{ old('metode_pembayaran_id', $pembelian->pembayarans->first()->metode_pembayaran_id ?? '') == $method->id ? 'selected' : '' }}>
                                 {{ $method->user->name ?? 'N/A' }} - {{ $method->bank }} ({{ $method->no_rekening }} a.n. {{ $method->atas_nama }})
                             </option>
                         @endforeach
                     </select>
                     @error('metode_pembayaran_id')
                         <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                     @enderror
                 </div>
             </div>
 
             <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                 <a href="{{ route('pembelian.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
                     Batal
                 </a>
                 <button type="submit" class="px-6 py-3 rounded-xl bg-[#001842] text-white font-semibold hover:bg-[#002a70] transition-colors">
                     Perbarui Transaksi
                 </button>
             </div>
         </form>
     </div>
 </div>
 
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const koperasiSelect = document.getElementById('koperasi_select');
         const petaniSelect = document.getElementById('petani_select');
         const statusSelect = document.getElementById('status_select');
         const paymentWrapper = document.getElementById('metode_pembayaran_wrapper');
         const paymentSelect = document.getElementById('metode_pembayaran_select');
         
         const jenisSelect = document.getElementById('jenis_kentang_select');
         const jumlahInput = document.getElementById('jumlah_kg_input');
         const totalHargaInput = document.getElementById('total_harga_input');
         const stokInfo = document.getElementById('stok_info');
         const stokText = document.getElementById('stok_text');
         const gudangText = document.getElementById('gudang_text');
         const hargaText = document.getElementById('harga_text');

         // Initialize Select2
         if (koperasiSelect) {
             $(koperasiSelect).select2({
                 placeholder: "Pilih Koperasi",
                 allowClear: true,
                 width: '100%'
             });
         }

         $(petaniSelect).select2({
             placeholder: "Pilih Petani",
             allowClear: true,
             width: '100%'
         });
         
         $(jenisSelect).select2({
             placeholder: "Pilih Jenis",
             allowClear: true,
             width: '100%'
         });

         $(paymentSelect).select2({
             placeholder: "Pilih Rekening Tujuan",
             allowClear: true,
             width: '100%'
         });

         // Format currency
         const formatRp = (angka) => {
             return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
         };

         function calculateHarga() {
             const selectedOption = jenisSelect.options[jenisSelect.selectedIndex];
             if(selectedOption && selectedOption.value) {
                 const harga = parseFloat(selectedOption.dataset.harga) || 0;
                 const jumlah = parseFloat(jumlahInput.value) || 0;
                 totalHargaInput.value = harga * jumlah;
             }
         }

         $(jenisSelect).on('select2:select select2:clear', function(e) {
             const selectedOption = this.options[this.selectedIndex];
             if(selectedOption && selectedOption.value) {
                 const stok = selectedOption.dataset.stok;
                 const gudang = selectedOption.dataset.gudang;
                 const harga = selectedOption.dataset.harga;
                 
                 stokText.textContent = stok;
                 if(gudangText) gudangText.textContent = gudang || 'Belum Ada Stok';
                 hargaText.textContent = formatRp(harga);
                 stokInfo.classList.remove('hidden');
             } else {
                 stokInfo.classList.add('hidden');
             }
             calculateHarga();
         });

         jumlahInput.addEventListener('input', calculateHarga);
         
         // Save copy of all options
         const allPaymentOptions = Array.from(paymentSelect.options).map(opt => ({
             value: opt.value,
             text: opt.text,
             userId: opt.getAttribute('data-user-id')
         }));
 
         function filterPaymentMethods() {
             const selectedPetaniId = petaniSelect.value;
             const currentSelectedValue = paymentSelect.value;
             
             // Clear current options except the first placeholder
             paymentSelect.innerHTML = '<option value="">Pilih Rekening Tujuan</option>';
             
             allPaymentOptions.forEach(opt => {
                 if (opt.value && opt.userId === selectedPetaniId) {
                     const newOpt = document.createElement('option');
                     newOpt.value = opt.value;
                     newOpt.text = opt.text;
                     newOpt.setAttribute('data-user-id', opt.userId);
                     if (opt.value === currentSelectedValue) {
                         newOpt.selected = true;
                     }
                     paymentSelect.appendChild(newOpt);
                 }
             });
         }
 
         function togglePaymentSelect() {
             if (statusSelect.value === 'lunas') {
                 paymentWrapper.classList.remove('hidden');
                 paymentSelect.setAttribute('required', 'required');
                 filterPaymentMethods();
             } else {
                 paymentWrapper.classList.add('hidden');
                 paymentSelect.removeAttribute('required');
                 paymentSelect.value = '';
             }
         }
 
         $(petaniSelect).on('select2:select select2:clear', filterPaymentMethods);
         statusSelect.addEventListener('change', togglePaymentSelect);
         
         // Trigger initial state
         togglePaymentSelect();
         if(jenisSelect.value) {
             const selectedOption = jenisSelect.options[jenisSelect.selectedIndex];
             const stok = selectedOption.dataset.stok;
             const harga = selectedOption.dataset.harga;
             stokText.textContent = stok;
             hargaText.textContent = formatRp(harga);
             stokInfo.classList.remove('hidden');
         }
     });
 </script>
 @endsection
