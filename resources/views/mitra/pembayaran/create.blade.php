@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('mitra.pembayaran.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Catat Pembayaran Baru</h1>
            <p class="text-xs text-slate-400">Buat laporan pelunasan atas tagihan pembelian hasil panen dari Koperasi.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 p-8 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-emerald-600 to-teal-600 absolute top-0 left-0"></div>
        
        <form action="{{ route('mitra.pembayaran.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Tagihan Summary Card -->
            <div class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 rounded-2xl p-6 flex items-center justify-between border border-emerald-100/80 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Koperasi Penjual</p>
                    <p id="summary-koperasi" class="font-extrabold text-slate-800 text-base">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Total Tagihan</p>
                    <p id="summary-total" class="text-2xl font-extrabold text-emerald-700 font-mono">Rp 0</p>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Pembelian Select -->
                <div class="space-y-2">
                    <label for="penjualan_buah_id" class="block text-sm font-semibold text-slate-700">Pilih Transaksi Pembelian</label>
                    <select name="penjualan_buah_id" id="penjualan_buah_id" 
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 select2 focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="" disabled selected>-- Pilih Transaksi Pembelian (Tagihan Belum Lunas) --</option>
                        @forelse($transaksis as $transaksi)
                            <option value="{{ $transaksi->id }}" data-koperasi="{{ $transaksi->koperasi->name ?? 'N/A' }}" data-total="{{ $transaksi->total_harga }}" {{ old('penjualan_buah_id', request('penjualan_id')) == $transaksi->id ? 'selected' : '' }}>
                                INV-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }} — {{ $transaksi->koperasi->name ?? 'Koperasi' }} — Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }} ({{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d M Y') }})
                            </option>
                        @empty
                            <option value="" disabled>-- Tidak ada tagihan transaksi yang belum lunas --</option>
                        @endforelse
                    </select>
                </div>

                <!-- Jumlah Bayar -->
                <div class="space-y-2">
                    <label for="jumlah_bayar" class="block text-sm font-semibold text-slate-700">Jumlah Uang Yang Dibayarkan (Rp)</label>
                    <input type="number" step="0.01" min="0.01" name="jumlah_bayar" id="jumlah_bayar" 
                        class="block w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono font-extrabold text-lg text-emerald-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
                </div>

                <!-- Metode Pembayaran Select -->
                <div class="space-y-2">
                    <label for="metode_pembayaran_id" class="block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                    <select name="metode_pembayaran_id" id="metode_pembayaran_id" 
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500 font-semibold" required>
                        <option value="" disabled selected>-- Pilih Metode Transfer --</option>
                        @foreach($methods as $method)
                            <option value="{{ $method->id }}">
                                🏢 {{ $method->kategori }} — {{ $method->bank }} (A/N {{ $method->atas_nama }} - {{ $method->no_rekening }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pembayaran & Catatan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="tanggal_pembayaran" class="block text-sm font-semibold text-slate-700">Tanggal Transfer</label>
                        <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" value="{{ date('Y-m-d') }}"
                            class="block w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" required>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="catatan" class="block text-sm font-semibold text-slate-700">Catatan Tambahan (Optional)</label>
                        <input type="text" name="catatan" id="catatan" placeholder="Nomor referensi bank, bukti transfer dll."
                            class="block w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('mitra.pembayaran.index') }}" 
                    class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold px-6 py-2.5 shadow-lg shadow-emerald-600/20 hover:scale-[1.01] active:scale-95 transition-all">
                    Konfirmasi & Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const selectTrx = document.getElementById('penjualan_buah_id');
        const summaryKoperasi = document.getElementById('summary-koperasi');
        const summaryTotal = document.getElementById('summary-total');
        const inputJumlahBayar = document.getElementById('jumlah_bayar');

        function updateSummary() {
            const selected = selectTrx.options[selectTrx.selectedIndex];
            if (selected && selected.value) {
                const koperasiName = selected.getAttribute('data-koperasi');
                const total = parseFloat(selected.getAttribute('data-total')) || 0;

                summaryKoperasi.textContent = '🏢 ' + koperasiName;
                summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                inputJumlahBayar.value = total;
            } else {
                summaryKoperasi.textContent = '-';
                summaryTotal.textContent = 'Rp 0';
                inputJumlahBayar.value = '';
            }
        }

        selectTrx.addEventListener('change', updateSummary);
        // Trigger on load for prepopulated transaction
        updateSummary();
    });
</script>
@endpush
@endsection
