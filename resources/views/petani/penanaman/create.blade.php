@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('penanaman.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tanam Benih Baru</h1>
            <p class="text-xs text-slate-400">Pilih stok benih dari gudang Anda untuk mulai ditanam.</p>
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

    <form action="{{ route('penanaman.store') }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-slate-100/60 border border-slate-100 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-500 to-green-600 absolute top-0 left-0"></div>
        @csrf
        
        <div class="space-y-6">
            <!-- Pilihan Gudang -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Pilih Gudang Penyimpanan Bibit</label>
                <select name="gudang_id" id="gudang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3" required>
                    <option value="">-- Pilih Gudang --</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                            🏢 {{ $gudang->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilihan Bibit yang tersedia -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Pilih Benih / Bibit yang Akan Ditanam</label>
                <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-2xl border-slate-200 px-4 py-3" required>
                    <option value="">-- Pilih Bibit --</option>
                    @foreach($availableSeeds as $seed)
                        <option value="{{ $seed->jenis_kentang_id }}" data-gudang="{{ $seed->gudang_id }}" data-max="{{ $seed->jumlah_stok }}" class="seed-option hidden">
                            🌱 {{ $seed->jenisKentang->nama_jenis }} (Tersedia: {{ number_format($seed->jumlah_stok, 0, ',', '.') }} Kg)
                        </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400">Hanya menampilkan benih yang ada di gudang terpilih.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Jumlah Tanam -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Jumlah Bibit (Kg)</label>
                    <input type="number" step="0.01" name="jumlah_tanam_kg" id="jumlah_tanam_kg" value="{{ old('jumlah_tanam_kg') }}" class="w-full rounded-2xl border-slate-200 px-4 py-3 text-slate-800 font-bold" placeholder="Misal: 50" required>
                </div>

                <!-- Estimasi Masa Panen -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Estimasi Masa Panen (Hari)</label>
                    <input type="number" name="estimasi_hari" value="{{ old('estimasi_hari', 100) }}" class="w-full rounded-2xl border-slate-200 px-4 py-3 text-slate-800" required>
                    <p class="text-[10px] text-slate-400">Rata-rata panen kentang adalah 90-110 hari.</p>
                </div>
            </div>

            <!-- Tanggal Tanam -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Tanggal Tanam</label>
                <input type="date" name="tanggal_tanam" value="{{ old('tanggal_tanam', date('Y-m-d')) }}" class="w-full rounded-2xl border-slate-200 px-4 py-3 text-slate-800" required>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('penanaman.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition-all">Tanam Sekarang</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gudangSelect = document.getElementById('gudang_id');
    const bibitSelect = document.getElementById('jenis_kentang_id');
    const bibitOptions = document.querySelectorAll('.seed-option');
    const inputJumlah = document.getElementById('jumlah_tanam_kg');

    function filterBibit() {
        const selectedGudang = gudangSelect.value;
        bibitSelect.value = ""; // Reset
        
        bibitOptions.forEach(opt => {
            if(opt.dataset.gudang === selectedGudang) {
                opt.classList.remove('hidden');
                opt.style.display = '';
            } else {
                opt.classList.add('hidden');
                opt.style.display = 'none';
            }
        });
    }

    gudangSelect.addEventListener('change', filterBibit);
    
    // Initial filter if gudang is pre-selected (old input)
    if(gudangSelect.value) {
        filterBibit();
    }

    bibitSelect.addEventListener('change', function() {
        const selectedOption = bibitSelect.options[bibitSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.max) {
            inputJumlah.max = selectedOption.dataset.max;
            inputJumlah.placeholder = `Maks: ${selectedOption.dataset.max} Kg`;
        } else {
            inputJumlah.removeAttribute('max');
            inputJumlah.placeholder = "Misal: 50";
        }
    });
});
</script>
@endpush
