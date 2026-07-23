@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Alokasi Stok Siap Dijual</h1>
        <p class="text-slate-500 text-sm">Tentukan berapa Kg dari stok fisik hasil panen yang tersimpan di gudang untuk dialokasikan siap dijual ke Koperasi. <em>(Volume fisik gudang tidak akan bertambah)</em>.</p>
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

    <form action="{{ route('stok.store') }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
        @csrf
        <div class="space-y-6">
            <!-- Select Gudang -->
            <label class="block space-y-2">
                <span class="font-bold text-slate-800 text-sm">Gudang Penyimpanan</span>
                <select name="gudang_id" id="gudang_id" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="">Pilih gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                            🏢 {{ $gudang->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- Select Jenis Kentang -->
            <label class="block space-y-2">
                <span class="font-bold text-slate-800 text-sm">Jenis Kentang</span>
                <select name="jenis_kentang_id" id="jenis_kentang_id" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="">Pilih jenis kentang</option>
                    @foreach($jenisKentangs as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_kentang_id') == $jenis->id ? 'selected' : '' }}>
                            🥔 {{ $jenis->nama_jenis }}
                        </option>
                    @endforeach
                </select>
            </label>

            <!-- Grade Kentang -->
            <label class="block space-y-2">
                <span class="font-bold text-slate-800 text-sm">Grade Kentang</span>
                <select name="grade" id="grade" class="w-full rounded-xl border px-4 py-3 select2" required>
                    <option value="A" {{ old('grade', 'A') == 'A' ? 'selected' : '' }}>Grade A (Bagus)</option>
                    <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>Grade B (Baik)</option>
                    <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>Grade C (Busuk)</option>
                </select>
            </label>

            <!-- Live Stok Physical Warehouse Indicator Panel -->
            <div id="harvest_stock_panel" class="hidden p-5 rounded-2xl border bg-slate-50 border-slate-200 transition-all space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span>📊</span> Status Stok Fisik Hasil Panen di Gudang
                    </span>
                    <span id="harvest_status_badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold">Checking...</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <!-- Card 1: Total Stok Fisik di Gudang -->
                    <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-2xs space-y-1">
                        <span class="text-blue-700 font-semibold block flex items-center gap-1">
                            🏢 Total Stok Fisik di Gudang
                        </span>
                        <strong id="harvest_total_text" class="text-blue-800 font-mono text-lg font-bold block">0 Kg</strong>
                        <p class="text-[10px] text-slate-500">Ketersediaan fisik murni dari pencatatan Panen.</p>
                    </div>

                    <!-- Card 2: Alokasi Siap Dijual Saat Ini -->
                    <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-2xs space-y-1">
                        <span class="text-emerald-700 font-semibold block flex items-center gap-1">
                            🛒 Alokasi Siap Dijual (Koperasi)
                        </span>
                        <strong id="harvest_dijual_text" class="text-emerald-800 font-mono text-lg font-bold block">0 Kg</strong>
                        <p class="text-[10px] text-slate-500">Jumlah Kg yang saat ini ditawarkan untuk dijual.</p>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-200/60">
                    <span>Sisa Stok Cadangan Mengendap: <strong id="harvest_tersimpan_text" class="text-slate-800 font-mono font-bold">0 Kg</strong></span>
                </div>
            </div>

            <!-- Input Jumlah Stok Dialokasikan Siap Dijual (Kg) + Quick Buttons -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-sm">Jumlah Stok Dialokasikan Siap Dijual (Kg)</span>
                    <button type="button" id="btn_fill_max_physical" class="hidden text-xs font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-200 transition-colors shadow-2xs">
                        ⚡ Alokasikan Seluruh Stok Gudang (<span id="btn_max_physical_label">0</span> Kg)
                    </button>
                </div>
                <input type="number" step="0.01" name="stok_dijual" id="stok_dijual" value="{{ old('stok_dijual') }}" 
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
            <button type="submit" class="rounded-xl bg-[#001842] hover:bg-[#002a70] px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-colors">Simpan Alokasi Stok</button>
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
    const harvestStockMap = {
        @foreach($existingStoks as $stk)
            "{{ $stk->gudang_id }}_{{ $stk->jenis_kentang_id }}_{{ $stk->grade }}": {
                gudang: {{ (float) $stk->total_gudang }},
                dijual: {{ (float) ($stk->total_dijual ?? $stk->total_gudang) }}
            },
        @endforeach
    };

    let maxPhysicalStock = 0;

    function checkHarvestStock() {
        const gudangId = $('#gudang_id').val();
        const jenisId = $('#jenis_kentang_id').val();
        const grade = $('#grade').val();

        if (gudangId && jenisId && grade) {
            const key = gudangId + '_' + jenisId + '_' + grade;
            const data = harvestStockMap[key] || { gudang: 0, dijual: 0 };
            const totalGudang = data.gudang;
            const stokDijual = data.dijual;
            const stokTersimpan = Math.max(0, totalGudang - stokDijual);

            maxPhysicalStock = totalGudang;

            $('#harvest_stock_panel').removeClass('hidden');

            $('#harvest_total_text').text(totalGudang.toLocaleString('id-ID') + ' Kg');
            $('#harvest_dijual_text').text(stokDijual.toLocaleString('id-ID') + ' Kg');
            $('#harvest_tersimpan_text').text(stokTersimpan.toLocaleString('id-ID') + ' Kg');

            if (totalGudang > 0) {
                $('#harvest_status_badge')
                    .removeClass('bg-slate-100 text-slate-600')
                    .addClass('bg-emerald-100 text-emerald-800')
                    .text('🟢 Stok Fisik Gudang: ' + totalGudang.toLocaleString('id-ID') + ' Kg');

                $('#btn_max_physical_label').text(totalGudang.toLocaleString('id-ID'));
                $('#btn_fill_max_physical').removeClass('hidden');
                $('#stok_dijual').attr('max', totalGudang);
            } else {
                $('#harvest_status_badge')
                    .removeClass('bg-emerald-100 text-emerald-800')
                    .addClass('bg-slate-100 text-slate-600')
                    .text('⚪ Tidak Ada Stok Fisik (0 Kg)');
                $('#btn_fill_max_physical').addClass('hidden');
                $('#stok_dijual').removeAttr('max');
            }
        } else {
            $('#harvest_stock_panel').addClass('hidden');
            $('#btn_fill_max_physical').addClass('hidden');
        }
    }

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        $('#gudang_id, #jenis_kentang_id, #grade').on('change', function() {
            checkHarvestStock();
        });

        $('#btn_fill_max_physical').on('click', function() {
            if (maxPhysicalStock > 0) {
                $('#stok_dijual').val(maxPhysicalStock);
            }
        });

        checkHarvestStock();
    });

    function addQty(amount) {
        let currentVal = parseFloat($('#stok_dijual').val()) || 0;
        let newVal = currentVal + amount;
        if (maxPhysicalStock > 0 && newVal > maxPhysicalStock) {
            newVal = maxPhysicalStock;
        }
        $('#stok_dijual').val(newVal);
    }

    function resetQty() {
        $('#stok_dijual').val('');
    }
</script>
@endpush
