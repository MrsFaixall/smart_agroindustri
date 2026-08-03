<section id="tentang" class="py-24 bg-gradient-to-b from-slate-50 to-white relative overflow-hidden">
    <!-- Decorative subtle gradients -->
    <div class="absolute top-1/4 left-0 w-72 h-72 bg-emerald-100/40 rounded-full blur-3xl opacity-60"></div>
    <div class="absolute bottom-1/4 right-0 w-80 h-80 bg-blue-100/30 rounded-full blur-3xl opacity-60"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left Side: Interactive Graphic/Illustration -->
            <div class="relative flex items-center justify-center">
                <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100 to-blue-50 rounded-full blur-3xl opacity-50 animate-pulse"></div>
                <div class="relative w-full max-w-lg">
                    <!-- Premium Farm/Agrotech Illustration -->
                    <img src="{{ asset('farm-illustration.png') }}?v={{ time() }}" alt="Tentang Agroindustri" class="rounded-3xl shadow-2xl border-4 border-white/60 bg-white w-full hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>

            <!-- Right Side: Content -->
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold uppercase tracking-wider outfit">
                    Tentang Kami
                </div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 outfit leading-tight">
                    Menghubungkan Ekosistem Pertanian dari Hulu ke Hilir
                </h2>
                <p class="text-base text-slate-600 leading-relaxed">
                    Smart Agroindustri lahir dengan visi mendigitalisasi rantai pasok komoditas pertanian nasional. Kami mengintegrasikan seluruh elemen esensial mulai dari mitra penyedia benih unggul, petani produsen, koperasi pengepul, hingga pasar dan konsumen akhir.
                </p>
                <p class="text-base text-slate-600 leading-relaxed">
                    Melalui transparansi harga, manajemen stok logistik digital, dan pembayaran instan terverifikasi (Midtrans), kami berkomitmen menciptakan keadilan perdagangan pertanian yang berkelanjutan dan memajukan kesejahteraan komunitas petani lokal.
                </p>
                
                <!-- Quick Stats Grid -->
                <div class="grid grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                    <div class="space-y-1">
                        <h4 class="text-3xl font-extrabold text-blue-600 outfit">Real-time</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Pencatatan Stok</p>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-3xl font-extrabold text-emerald-600 outfit">100%</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Transaksi Aman</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
