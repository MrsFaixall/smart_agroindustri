@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Edit Panen</h1>
        <p class="text-slate-500">Perbarui data panen.</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl bg-rose-50 px-4 py-3 text-rose-700">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('panen.update', $panen) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
        @csrf
        @method('PUT')
        <div class="grid gap-5 md:grid-cols-2">
            <label class="space-y-2">
                <span class="font-medium">Tanggal Panen</span>
                <input type="date" name="tanggal_panen" value="{{ old('tanggal_panen', $panen->tanggal_panen->format('Y-m-d')) }}" class="w-full rounded-xl border px-4 py-3" required>
            </label>
            <label class="space-y-2">
                <span class="font-medium">Jumlah (Kg)</span>
                <input type="number" step="0.01" name="jumlah_kg" value="{{ old('jumlah_kg', $panen->jumlah_kg) }}" class="w-full rounded-xl border px-4 py-3" required>
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Jenis Kentang</span>
                <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="">Pilih jenis kentang</option>
                    @foreach($jenisKentangs as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id', $panen->jenis_kentang_id) == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Gudang Tujuan</span>
                <select name="gudang_id" id="gudang_id" class="w-full rounded-xl border px-4 py-3 text-slate-800 select2" required>
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
                            {{ $gudang->nama_gudang }} — {{ $isFull ? '⚠️ PENUH (Sisa 0 Kg)' : 'Sisa Kapasitas: ' . number_format($sisa, 0, ',', '.') . ' Kg / Max ' . number_format($gudang->kapasitas_max, 0, ',', '.') . ' Kg' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400">Pastikan memilih gudang yang masih memiliki sisa kapasitas cukup.</p>
            </label>
            <label class="space-y-2 md:col-span-2">
                <span class="font-medium">Grade Kentang</span>
                <select name="grade" id="grade" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="A" {{ old('grade', $panen->grade) == 'A' ? 'selected' : '' }}>Grade A (Bagus)</option>
                    <option value="B" {{ old('grade', $panen->grade) == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                    <option value="C" {{ old('grade', $panen->grade) == 'C' ? 'selected' : '' }}>Grade C (Busuk)</option>
                </select>
            </label>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('panen.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm">Batal</a>
            <button type="submit" class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white">Perbarui</button>
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
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endpush
