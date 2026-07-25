@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('stok.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Alokasi Stok Siap Dijual</h1>
            <p class="text-xs text-slate-400">Perbarui alokasi stok komoditas yang siap dijual ke Koperasi.</p>
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

    <form action="{{ route('stok.update', $stok) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-indigo-600 to-blue-600 absolute top-0 left-0"></div>
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <!-- Gudang -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Gudang Penyimpanan</label>
                <select name="gudang_id" id="gudang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">Pilih gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}" {{ old('gudang_id', $stok->gudang_id) == $gudang->id ? 'selected' : '' }}>
                            🏢 {{ $gudang->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Jenis Kentang -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Jenis Kentang</label>
                <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">Pilih jenis kentang</option>
                    @foreach($jenisKentangs as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $stok->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>
                            🥔 {{ $jenis->nama_jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Grade Kentang -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Grade Kentang</label>
                <select name="grade" id="grade" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="A" {{ old('grade', $stok->grade) == 'A' ? 'selected' : '' }}>Grade A (Bagus)</option>
                    <option value="B" {{ old('grade', $stok->grade) == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                    <option value="C" {{ old('grade', $stok->grade) == 'C' ? 'selected' : '' }}>Grade C (Busuk)</option>
                </select>
            </div>

            <!-- Physical Stock Info Panel -->
            <div class="p-5 rounded-2xl border bg-slate-50/80 border-slate-200 transition-all space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span>📊</span> Status Stok Fisik Hasil Panen di Gudang
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        🟢 Total Fisik: {{ number_format($totalFisikGudang, 0, ',', '.') }} Kg
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-2xs space-y-1">
                        <span class="text-blue-700 font-semibold block">🏢 Total Fisik Gudang</span>
                        <strong class="text-blue-800 font-mono text-lg font-bold block">{{ number_format($totalFisikGudang, 0, ',', '.') }} Kg</strong>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-2xs space-y-1">
                        <span class="text-emerald-700 font-semibold block">🛒 Alokasi Siap Dijual Saat Ini</span>
                        <strong class="text-emerald-800 font-mono text-lg font-bold block">{{ number_format($stok->stok_dijual ?? $stok->jumlah_stok, 0, ',', '.') }} Kg</strong>
                    </div>
                </div>
            </div>

            <!-- Input Jumlah Stok Dialokasikan -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-semibold text-slate-700">Jumlah Stok Dialokasikan Siap Dijual (Kg)</label>
                    <button type="button" onclick="fillMax({{ $totalFisikGudang }})" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-200 transition-colors shadow-2xs">
                        ⚡ Set Max Fisik ({{ number_format($totalFisikGudang, 0, ',', '.') }} Kg)
                    </button>
                </div>
                <input type="number" step="0.01" name="stok_dijual" id="stok_dijual" max="{{ $totalFisikGudang }}" value="{{ old('stok_dijual', $stok->stok_dijual ?? $stok->jumlah_stok) }}" 
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-bold text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" 
                    placeholder="Masukkan jumlah Kg..." required>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('stok.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition-all">Perbarui Alokasi Stok</button>
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
        transition: all 0.2s ease;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
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
        border: 1px solid #6366f1 !important;
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
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #6366f1 !important;
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
            width: '100%'
        });
    });

    function fillMax(maxVal) {
        $('#stok_dijual').val(maxVal);
    }
</script>
@endpush
