@extends('layouts.app')

@push('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Midtrans Snap JS -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransClientKey }}"></script>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pt-4">
    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-start gap-4">
            <div class="bg-red-50 p-3 rounded-xl text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Pembayaran Transaksi</h1>
                <p class="text-slate-500 text-sm mt-1">Selesaikan rincian transaksi pembayaran Anda</p>
            </div>
        </div>

        <form action="{{ route('pembayaran.store') }}" method="POST" id="payment-form" class="p-6 space-y-6">
            @csrf

            <!-- Tagihan Summary -->
            <div class="bg-slate-50 rounded-xl p-5 flex items-center justify-between border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pelanggan / Petani</p>
                    <p id="summary-petani" class="font-bold text-slate-800">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Tagihan</p>
                    <p id="summary-total" class="text-xl font-bold text-emerald-600">Rp 0</p>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Pembelian Select -->
                <div class="space-y-2">
                    <label for="pembelian_id" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Transaksi</label>
                    <select name="pembelian_id" id="pembelian_id" 
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white transition-all select2" required>
                        <option value="" disabled selected>-- Pilih Transaksi Pembelian (Tagihan Belum Lunas) --</option>
                        @forelse($pembelians as $pembelian)
                            <option value="{{ $pembelian->id }}" data-petani="{{ $pembelian->petani->name ?? 'N/A' }}" data-total="{{ $pembelian->total_harga }}" {{ old('pembelian_id', request('pembelian_id')) == $pembelian->id ? 'selected' : '' }}>
                                {{ $pembelian->kode_trx }} — {{ $pembelian->petani->name ?? 'Petani' }} — Koperasi: {{ $pembelian->koperasi->name ?? '-' }} — Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }} ({{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->format('d M Y') }})
                            </option>
                        @empty
                            <option value="" disabled>-- Tidak ada tagihan transaksi yang belum lunas --</option>
                        @endforelse
                    </select>
                </div>

                <!-- Metode Pembayaran Custom Select -->
                <div class="space-y-2" x-data="{ 
                    open: false, 
                    selectedMethod: '{{ old('metode_pembayaran_id', 'midtrans') }}',
                    methods: [
                        { id: 'other_qris', label: 'QRIS', type: 'midtrans_qris', icon_url: 'https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg' },
                        { id: 'gopay', label: 'GoPay', type: 'midtrans_qris', icon_url: 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg' },
                        { id: 'shopeepay', label: 'ShopeePay', type: 'midtrans_qris', icon_url: 'https://upload.wikimedia.org/wikipedia/commons/f/fe/ShopeePay.png' },
                        { id: 'bca_va', label: 'BCA Virtual Account', type: 'midtrans_va', icon_url: 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg' },
                        { id: 'bni_va', label: 'BNI Virtual Account', type: 'midtrans_va', icon_url: 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg' },
                        { id: 'bri_va', label: 'BRI Virtual Account', type: 'midtrans_va', icon_url: 'https://upload.wikimedia.org/wikipedia/commons/9/9e/BRI_2020.svg' },
                        { id: 'echannel', label: 'Mandiri Virtual Account', type: 'midtrans_va', icon_url: 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg' },
                        { id: 'midtrans', label: 'Lihat Semua Metode Midtrans', type: 'midtrans_all', icon_text: 'MID' },
                        @foreach($methods as $method)
                        { id: '{{ $method->id }}', label: '{{ $method->kategori ?? 'Bank' }} - {{ $method->bank }} ({{ $method->no_rekening }})', desc: 'a.n {{ $method->atas_nama }}', type: 'manual', icon_text: 'MAN' },
                        @endforeach
                    ],
                    get selectedLabel() {
                        let m = this.methods.find(m => m.id == this.selectedMethod);
                        return m ? m.label : 'Pilih Metode Pembayaran';
                    },
                    get selectedIcon() {
                        let m = this.methods.find(m => m.id == this.selectedMethod);
                        return m ? m.icon_url : null;
                    }
                }" x-init="$watch('selectedMethod', val => { 
                    $refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true })); 
                })">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Metode Pembayaran</label>
                    
                    <!-- Hidden input untuk disubmit & dibaca js -->
                    <input type="hidden" name="metode_pembayaran_id" id="metode_pembayaran_id" x-model="selectedMethod" x-ref="hiddenInput">

                    <!-- Custom Select Trigger -->
                    <div class="relative">
                        <button type="button" @click="open = !open" 
                            class="w-full flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white transition-all text-left">
                            <div class="flex items-center gap-3">
                                <template x-if="selectedIcon">
                                    <div class="w-8 h-8 rounded bg-white flex items-center justify-center p-0.5 border border-slate-100 shadow-sm">
                                        <img :src="selectedIcon" class="max-w-full max-h-full object-contain">
                                    </div>
                                </template>
                                <template x-if="!selectedIcon">
                                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                </template>
                                <span class="font-medium text-slate-800" x-text="selectedLabel"></span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Custom Dropdown List -->
                        <div x-show="open" x-cloak @click.away="open = false" x-transition 
                            class="absolute z-10 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl max-h-80 overflow-y-auto">
                            
                            <div class="p-2">
                                <!-- Group Qris -->
                                <div class="mb-2">
                                    <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">QRIS & Dompet Digital</p>
                                    <template x-for="m in methods.filter(m => m.type === 'midtrans_qris')" :key="m.id">
                                        <button type="button" @click="selectedMethod = m.id; open = false" 
                                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors"
                                            :class="selectedMethod == m.id ? 'bg-blue-50/50' : ''">
                                            <div class="flex items-center gap-3">
                                                <template x-if="m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-white flex items-center justify-center p-1 border border-slate-100 shadow-sm">
                                                        <img :src="m.icon_url" class="max-w-full max-h-full object-contain">
                                                    </div>
                                                </template>
                                                <template x-if="!m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-blue-100 flex items-center justify-center text-blue-600 text-[10px] font-bold" x-text="m.icon_text"></div>
                                                </template>
                                                <div class="text-left">
                                                    <p class="text-sm font-bold text-slate-800" x-text="m.label"></p>
                                                </div>
                                            </div>
                                            <svg x-show="selectedMethod == m.id" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </template>
                                </div>
                                <div class="h-px bg-slate-100 my-2"></div>
                                
                                <!-- Group VA -->
                                <div class="mb-2">
                                    <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Virtual Account / Transfer Bank</p>
                                    <template x-for="m in methods.filter(m => m.type === 'midtrans_va')" :key="m.id">
                                        <button type="button" @click="selectedMethod = m.id; open = false" 
                                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors"
                                            :class="selectedMethod == m.id ? 'bg-blue-50/50' : ''">
                                            <div class="flex items-center gap-3">
                                                <template x-if="m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-white flex items-center justify-center p-1 border border-slate-100 shadow-sm">
                                                        <img :src="m.icon_url" class="max-w-full max-h-full object-contain">
                                                    </div>
                                                </template>
                                                <template x-if="!m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-blue-100 flex items-center justify-center text-blue-600 text-[10px] font-bold" x-text="m.icon_text"></div>
                                                </template>
                                                <div class="text-left">
                                                    <p class="text-sm font-bold text-slate-800" x-text="m.label"></p>
                                                </div>
                                            </div>
                                            <svg x-show="selectedMethod == m.id" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </template>
                                </div>
                                <div class="h-px bg-slate-100 my-2"></div>
                                
                                <!-- All Midtrans -->
                                <div class="mb-2">
                                    <template x-for="m in methods.filter(m => m.type === 'midtrans_all')" :key="m.id">
                                        <button type="button" @click="selectedMethod = m.id; open = false" 
                                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors"
                                            :class="selectedMethod == m.id ? 'bg-blue-50/50' : ''">
                                            <div class="flex items-center gap-3">
                                                <template x-if="m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-white flex items-center justify-center p-1 border border-slate-100 shadow-sm">
                                                        <img :src="m.icon_url" class="max-w-full max-h-full object-contain">
                                                    </div>
                                                </template>
                                                <template x-if="!m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-blue-100 flex items-center justify-center text-blue-600 text-[10px] font-bold" x-text="m.icon_text"></div>
                                                </template>
                                                <div class="text-left">
                                                    <p class="text-sm font-bold text-slate-800" x-text="m.label"></p>
                                                    <p class="text-[11px] text-slate-500">Pilih dari popup Midtrans</p>
                                                </div>
                                            </div>
                                            <svg x-show="selectedMethod == m.id" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </template>
                                </div>

                                @if($methods->count() > 0)
                                <div class="h-px bg-slate-100 my-2"></div>
                                <!-- Group Manual -->
                                <div>
                                    <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Transfer Manual (Koperasi ke Petani)</p>
                                    <template x-for="m in methods.filter(m => m.type === 'manual')" :key="m.id">
                                        <button type="button" @click="selectedMethod = m.id; open = false" 
                                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors"
                                            :class="selectedMethod == m.id ? 'bg-blue-50/50' : ''">
                                            <div class="flex items-center gap-3">
                                                <template x-if="m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-white flex items-center justify-center p-1 border border-slate-100 shadow-sm">
                                                        <img :src="m.icon_url" class="max-w-full max-h-full object-contain">
                                                    </div>
                                                </template>
                                                <template x-if="!m.icon_url">
                                                    <div class="w-10 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-500 text-[10px] font-bold" x-text="m.icon_text"></div>
                                                </template>
                                                <div class="text-left">
                                                    <p class="text-sm font-bold text-slate-800" x-text="m.label"></p>
                                                    <p class="text-[11px] text-slate-500" x-text="m.desc"></p>
                                                </div>
                                            </div>
                                            <svg x-show="selectedMethod == m.id" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </template>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Bayar Input -->
                <div class="space-y-2">
                    <label for="jumlah_bayar" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Jumlah Bayar (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                        <input type="number" step="0.01" min="0.01" name="jumlah_bayar" id="jumlah_bayar" value="{{ old('jumlah_bayar') }}" placeholder="0" 
                            class="w-full rounded-xl border border-slate-200 pl-10 pr-4 py-3 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Tanggal Pembayaran Input -->
                    <div class="space-y-2">
                        <label for="tanggal_pembayaran" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal</label>
                        <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" 
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all" required>
                    </div>

                    <!-- Status Select -->
                    <div class="space-y-2">
                        <label for="status" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status</label>
                        <select name="status" id="status" 
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white transition-all text-amber-600" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="belum lunas" {{ old('status') == 'belum lunas' ? 'selected' : '' }} class="text-slate-700">Belum Lunas</option>
                            <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }} class="text-emerald-600">Lunas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-6">
                <a href="{{ route('pembayaran.index') }}" class="rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <div class="flex gap-3">
                    <button type="submit" id="submit-manual" class="hidden rounded-xl bg-[#001842] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#002a70] transition-colors">
                        Simpan Pembayaran
                    </button>
                    <button type="button" id="pay-midtrans" class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Bayar via Midtrans
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pembelianSelect = document.getElementById('pembelian_id');
        const metodeSelect = document.getElementById('metode_pembayaran_id');
        const jumlahBayarInput = document.getElementById('jumlah_bayar');
        const summaryPetani = document.getElementById('summary-petani');
        const summaryTotal = document.getElementById('summary-total');
        
        const btnManual = document.getElementById('submit-manual');
        const btnMidtrans = document.getElementById('pay-midtrans');
        const form = document.getElementById('payment-form');

        // Initialize Select2
        $(pembelianSelect).select2({
            placeholder: "-- Pilih Transaksi Pembelian --",
            allowClear: true
        });

        // Format currency
        const formatRp = (angka) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        };

        // Update summary on pembelian change (using jQuery for Select2 event)
        $(pembelianSelect).on('select2:select', function (e) {
            updateSummary();
        });
        
        function updateSummary() {
            const selected = pembelianSelect.options[pembelianSelect.selectedIndex];
            if(selected && selected.value) {
                const total = parseFloat(selected.dataset.total);
                summaryPetani.textContent = selected.dataset.petani;
                summaryTotal.textContent = formatRp(total);
                
                // Auto fill jumlah bayar if empty
                if(!jumlahBayarInput.value) {
                    jumlahBayarInput.value = total;
                }
            } else {
                summaryPetani.textContent = '-';
                summaryTotal.textContent = 'Rp 0';
                jumlahBayarInput.value = '';
            }
        }

        // Toggle buttons based on method
        metodeSelect.addEventListener('change', function() {
            // Jika valuenya bukan angka murni, berarti itu Midtrans (midtrans, gopay, bca_va, dll)
            if(isNaN(this.value)) {
                btnMidtrans.classList.remove('hidden');
                btnManual.classList.add('hidden');
            } else {
                btnMidtrans.classList.add('hidden');
                btnManual.classList.remove('hidden');
            }
        });

        // Trigger change to set initial state
        if(pembelianSelect.value) pembelianSelect.dispatchEvent(new Event('change'));
        if(metodeSelect.value) metodeSelect.dispatchEvent(new Event('change'));

        // Handle Midtrans Payment
        btnMidtrans.addEventListener('click', async function() {
            const pembelianId = pembelianSelect.value;
            const jumlahBayar = jumlahBayarInput.value;
            const paymentType = metodeSelect.value; // e.g. 'gopay', 'bca_va', 'midtrans'

            if (!pembelianId || !jumlahBayar) {
                alert('Pilih transaksi dan isi jumlah bayar terlebih dahulu!');
                return;
            }

            const originalText = btnMidtrans.innerHTML;
            btnMidtrans.disabled = true;
            btnMidtrans.innerHTML = 'Memproses...';

            try {
                const response = await fetch("{{ route('midtrans.snap-token') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pembelian_id: pembelianId,
                        jumlah_bayar: jumlahBayar,
                        payment_type: paymentType
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            window.location.href = "{{ route('pembayaran.finish') }}?order_id=" + result.order_id + "&transaction_status=" + result.transaction_status;
                        },
                        onPending: function(result){
                            window.location.href = "{{ route('pembayaran.finish') }}?order_id=" + result.order_id + "&transaction_status=" + result.transaction_status;
                        },
                        onError: function(result){
                            alert('Pembayaran gagal!');
                        },
                        onClose: function(){
                            alert('Anda menutup popup sebelum menyelesaikan pembayaran');
                        }
                    });
                } else {
                    alert(data.message || data.error || 'Terjadi kesalahan saat memproses pembayaran');
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi atau parsing: ' + error.message);
            } finally {
                btnMidtrans.disabled = false;
                btnMidtrans.innerHTML = originalText;
            }
        });
    });
</script>
@endsection
