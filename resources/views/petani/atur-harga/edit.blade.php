@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('atur-harga.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Harga Kentang</h1>
            <p class="text-xs text-slate-400">Perbarui harga acuan untuk jenis kentang pilihan.</p>
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

    <form action="{{ route('atur-harga.update', $price) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-amber-500 to-orange-600 absolute top-0 left-0"></div>
        @csrf
        @method('PUT')

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Jenis Kentang</label>
            <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                <option value="">Pilih jenis kentang</option>
                @foreach($jenisKentangs as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $price->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700">Harga Anda (Rp / Kg)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold text-sm">Rp</span>
                <input type="number" step="0.01" name="harga" value="{{ old('harga', $price->harga) }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-slate-800 font-bold focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none" required>
            </div>
            <p class="text-xs text-slate-500 mt-2 font-medium" id="hargaPasarHint">
                @if($hargaPasar)
                    Harga Pasar Saat Ini: <strong class="text-amber-700">Rp {{ number_format($hargaPasar->harga, 0, ',', '.') }}</strong>
                @else
                    Belum ada patokan Harga Pasar dari Koperasi untuk jenis ini.
                @endif
            </p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('atur-harga.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/30 transition-all">Perbarui Harga</button>
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
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2) !important;
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
        border: 1px solid #f59e0b !important;
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
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2) !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #f59e0b !important;
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
</script>
@endpush
