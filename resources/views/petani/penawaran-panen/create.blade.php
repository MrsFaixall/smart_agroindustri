@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('petani.penawaran-panen.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ajukan Penawaran Penjualan</h1>
            <p class="text-xs text-slate-400">Tawarkan stok hasil panen Anda ke Koperasi.</p>
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

    <form action="{{ route('petani.penawaran-panen.store') }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-blue-500 to-indigo-600 absolute top-0 left-0"></div>
        @csrf
        
        <div class="space-y-6">
            <!-- Pilihan Stok -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Pilih Stok Kentang Konsumsi (Siap Jual)</label>
                <select name="stok_id" id="stok_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">-- Pilih Stok dari Gudang --</option>
                    @foreach($stoks as $stok)
                        <option value="{{ $stok->id }}" data-max="{{ $stok->stok_dijual }}" data-jenis="{{ $stok->jenis_kentang_id }}" {{ old('stok_id', $selectedStok->id ?? '') == $stok->id ? 'selected' : '' }}>
                            {{ $stok->jenisKentang->nama_jenis }} (Siap Jual: {{ number_format($stok->stok_dijual, 0, ',', '.') }} Kg) - {{ $stok->gudang->nama_gudang }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400">Hanya menampilkan kentang konsumsi yang ada di gudang Anda.</p>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Pilih Koperasi Tujuan</label>
                <select name="koperasi_id" id="koperasi_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">-- Pilih Koperasi --</option>
                    @foreach($koperasis as $koperasi)
                        <option value="{{ $koperasi->id }}" {{ old('koperasi_id') == $koperasi->id ? 'selected' : '' }}>
                            🏢 {{ $koperasi->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Jumlah Tawar (Kg)</label>
                    <input type="number" step="0.01" name="jumlah_kg" id="jumlah_kg" value="{{ old('jumlah_kg') }}" class="w-full rounded-2xl border-slate-200 px-4 py-3 text-slate-800 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="Misal: 50" required>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Harga Tawar Petani (Rp/Kg)</label>
                    <input type="number" name="harga_tawaran_petani" id="harga_tawaran_petani" value="{{ old('harga_tawaran_petani') }}" class="w-full rounded-2xl border-slate-200 px-4 py-3 text-slate-800 font-bold bg-slate-50 cursor-not-allowed focus:ring-0" placeholder="Otomatis dari Atur Harga" readonly required>
                    <p class="text-[10px] text-slate-500 mt-1">Harga ini dikunci dari pengaturan <a href="{{ route('atur-harga.index') }}" class="text-blue-600 font-bold hover:underline">Harga Jual Anda</a>.</p>
                    <div id="hargaHints" class="hidden mt-3 p-3 bg-blue-50/50 rounded-xl border border-blue-100 text-xs space-y-1">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Harga Pasar (Acuan Koperasi):</span>
                            <span id="hintPasar" class="font-bold text-amber-700">-</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Harga Patokan Anda:</span>
                            <span id="hintPetani" class="font-bold text-blue-700">-</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Harga -->
            <div class="bg-indigo-50/50 border border-indigo-100 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-indigo-900">Total Harga Penawaran</h3>
                    <p class="text-xs text-indigo-700/70">Estimasi total harga (Jumlah x Harga/Kg)</p>
                </div>
                <div class="text-xl md:text-2xl font-extrabold text-indigo-700 font-mono" id="totalHargaDisplay">
                    Rp 0
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('petani.penawaran-panen.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all">Ajukan Penawaran</button>
        </div>
    </form>
</div>

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
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
        
        const hargaPasars = @json($hargaPasars);
        const hargaPetanis = @json($hargaPetanis);
        
        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }
        
        function hitungTotal() {
            let harga = parseFloat($('#harga_tawaran_petani').val()) || 0;
            let jumlah = parseFloat($('#jumlah_kg').val()) || 0;
            let total = harga * jumlah;
            $('#totalHargaDisplay').text(formatRupiah(total));
        }
        
        function updateHints() {
            let selectedOpt = $('#stok_id').find(':selected');
            let jenis_id = selectedOpt.data('jenis');
            
            if(jenis_id) {
                $('#hargaHints').removeClass('hidden');
                
                if(hargaPasars[jenis_id]) {
                    $('#hintPasar').text(formatRupiah(hargaPasars[jenis_id].harga));
                } else {
                    $('#hintPasar').html('<span class="text-slate-400 font-normal">Belum diatur</span>');
                }
                
                if(hargaPetanis[jenis_id]) {
                    $('#hintPetani').text(formatRupiah(hargaPetanis[jenis_id].harga));
                    $('#harga_tawaran_petani').val(hargaPetanis[jenis_id].harga);
                } else {
                    $('#hintPetani').html('<span class="text-rose-500 font-bold">Belum diatur! Harap atur dulu.</span>');
                    $('#harga_tawaran_petani').val('');
                }
            } else {
                $('#hargaHints').addClass('hidden');
                $('#harga_tawaran_petani').val('');
            }
            hitungTotal();
        }
        
        $('#stok_id').on('change', function() {
            let max = $(this).find(':selected').data('max');
            $('#jumlah_kg').attr('max', max);
            updateHints();
        });
        
        $('#jumlah_kg').on('input', function() {
            hitungTotal();
        });
        
        // Inisialisasi awal
        if($('#stok_id').val()) {
            $('#stok_id').trigger('change');
        }
    });
</script>
@endpush
@endsection
