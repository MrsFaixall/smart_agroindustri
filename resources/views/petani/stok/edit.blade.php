@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Edit Alokasi Stok Siap Dijual</h1>
        <p class="text-slate-500 text-sm">Perbarui alokasi stok komoditas yang siap dijual ke Koperasi.</p>
    </div>

    @if($errors->any())
        <div class="rounded-2xl bg-rose-50 border border-rose-200 px-5 py-4 text-rose-700">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stok.update', $stok) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <!-- Gudang -->
            <label class="block space-y-2">
                <span class="font-bold text-slate-800 text-sm">Gudang Penyimpanan</span>
                <select name="gudang_id" id="gudang_id" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="">Pilih gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}" {{ old('gudang_id', $stok->gudang_id) == $gudang->id ? 'selected' : '' }}>
                            🏢 {{ $gudang->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- Jenis Kentang -->
            <label class="block space-y-2">
                <span class="font-bold text-slate-800 text-sm">Jenis Kentang</span>
                <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="">Pilih jenis kentang</option>
                    @foreach($jenisKentangs as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $stok->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>
                            🥔 {{ $jenis->nama_jenis }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- Grade Kentang -->
            <label class="block space-y-2">
                <span class="font-bold text-slate-800 text-sm">Grade Kentang</span>
                <select name="grade" id="grade" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="A" {{ old('grade', $stok->grade) == 'A' ? 'selected' : '' }}>Grade A (Bagus)</option>
                    <option value="B" {{ old('grade', $stok->grade) == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                    <option value="C" {{ old('grade', $stok->grade) == 'C' ? 'selected' : '' }}>Grade C (Busuk)</option>
                </select>
            </label>

            <!-- Physical Stock Info Panel -->
            <div class="p-5 rounded-2xl border bg-slate-50 border-slate-200 space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700">🏢 Stok Fisik Tersimpan di Gudang:</span>
                    <strong class="font-mono text-slate-900 text-sm font-bold">{{ number_format($stok->jumlah_stok, 0, ',', '.') }} Kg</strong>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 border-t border-slate-200/60 pt-2">
                    <span>Sisa Stok Cadangan:</span>
                    <strong class="font-mono text-slate-700">{{ number_format(max(0, $stok->jumlah_stok - ($stok->stok_dijual ?? $stok->jumlah_stok)), 0, ',', '.') }} Kg</strong>
                </div>
            </div>

            <!-- Input Stok Siap Dijual -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-sm">Jumlah Stok Dialokasikan Siap Dijual (Kg)</span>
                    <button type="button" onclick="$('#stok_dijual').val({{ $stok->jumlah_stok }})" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-200 transition-colors shadow-2xs">
                        ⚡ Alokasikan Seluruh Stok Gudang ({{ number_format($stok->jumlah_stok, 0, ',', '.') }} Kg)
                    </button>
                </div>
                <input type="number" step="0.01" name="stok_dijual" id="stok_dijual" value="{{ old('stok_dijual', $stok->stok_dijual ?? $stok->jumlah_stok) }}" 
                    max="{{ $stok->jumlah_stok }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono font-semibold text-slate-900 focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-colors" 
                    placeholder="Masukkan jumlah Kg yang mau dijual ke Koperasi..." required>
                
                <!-- Presets -->
                <div class="flex items-center gap-2 pt-1 flex-wrap text-xs">
                    <span class="text-slate-400 font-medium text-[11px]">Quick Add:</span>
                    <button type="button" onclick="addQty(100)" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg border border-slate-200 transition-colors">+100 Kg</button>
                    <button type="button" onclick="addQty(500)" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg border border-slate-200 transition-colors">+500 Kg</button>
                    <button type="button" onclick="addQty(1000)" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg border border-slate-200 transition-colors">+1.000 Kg</button>
                    <button type="button" onclick="addQty(2000)" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg border border-slate-200 transition-colors">+2.000 Kg</button>
                    <button type="button" onclick="resetQty()" class="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg border border-rose-200 transition-colors">Reset</button>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('stok.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="rounded-xl bg-[#001842] hover:bg-[#002a70] px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-colors">Perbarui Alokasi Stok</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 1px solid #cbd5e1 !important;
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const maxStock = {{ (float) $stok->jumlah_stok }};

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });

    function addQty(amount) {
        let currentVal = parseFloat($('#stok_dijual').val()) || 0;
        let newVal = currentVal + amount;
        if (maxStock > 0 && newVal > maxStock) {
            newVal = maxStock;
        }
        $('#stok_dijual').val(newVal);
    }

    function resetQty() {
        $('#stok_dijual').val('');
    }
</script>
@endpush
