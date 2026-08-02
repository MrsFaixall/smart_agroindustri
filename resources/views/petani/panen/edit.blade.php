@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('panen.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Panen</h1>
            <p class="text-xs text-slate-400">Perbarui data pencatatan hasil panen.</p>
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

    <form action="{{ route('panen.update', $panen) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-500 to-teal-600 absolute top-0 left-0"></div>
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Tanggal Panen</label>
                <input type="date" name="tanggal_panen" value="{{ old('tanggal_panen', $panen->tanggal_panen->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Jumlah (Kg)</label>
                <input type="number" step="0.01" name="jumlah_kg" value="{{ old('jumlah_kg', $panen->jumlah_kg) }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Jenis Kentang</label>
                <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">Pilih jenis kentang</option>
                    @foreach($jenisKentangs as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $panen->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Gudang Tujuan</label>
                <select name="gudang_id" id="gudang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="">Pilih gudang</option>
                    @foreach($gudangs as $gudang)
                        @php
                            $terpakai = $gudang->kapasitas_terpakai;
                            if ($panen->gudang_id == $gudang->id) {
                                $terpakai -= $panen->jumlah_kg;
                            }
                            $sisa = $gudang->kapasitas_max - $terpakai;
                            $isFull = $sisa <= 0;
                        @endphp
                        <option value="{{ $gudang->id }}" {{ old('gudang_id', $panen->gudang_id) == $gudang->id ? 'selected' : '' }}>
                            {{ $gudang->nama_gudang }} (Petani: {{ $gudang->user->name ?? 'Belum Diketahui' }}) — {{ $isFull ? '⚠️ PENUH (Sisa 0 Kg)' : 'Sisa Kapasitas: ' . number_format($sisa, 0, ',', '.') . ' Kg / Max ' . number_format($gudang->kapasitas_max, 0, ',', '.') . ' Kg' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400">Pastikan memilih gudang yang masih memiliki sisa kapasitas cukup.</p>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">Grade Kentang</label>
                <select name="grade" id="grade" class="w-full rounded-2xl border-slate-200 px-4 py-3 select2" required>
                    <option value="A" {{ old('grade', $panen->grade) == 'A' ? 'selected' : '' }}>Grade A (Bagus)</option>
                    <option value="B" {{ old('grade', $panen->grade) == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                    <option value="C" {{ old('grade', $panen->grade) == 'C' ? 'selected' : '' }}>Grade C (Busuk)</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('panen.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition-all">Perbarui Data Panen</button>
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
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
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
        border: 1px solid #10b981 !important;
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
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
    }
    .select2-results__option {
        font-size: 0.875rem !important;
        padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #10b981 !important;
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
