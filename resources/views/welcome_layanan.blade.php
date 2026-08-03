<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layanan Kami - Smart Agroindustri</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .outfit { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-800">

    <!-- Navbar -->
    @include('partials.welcome-navbar')

    <!-- Header Hero Banner -->
    <div class="relative pt-32 pb-16 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center py-12 md:py-20">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-300 text-xs font-semibold mb-4 uppercase tracking-widest outfit">
                Ekosistem Digital
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 outfit">Layanan Kami</h1>
            <p class="text-slate-300 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                Menghadirkan solusi digital komprehensif bagi seluruh pelaku rantai nilai pertanian dari hulu ke hilir.
            </p>
        </div>
    </div>

    <!-- Services Detail Section -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
                
                <!-- Service 1: Mitra Benih -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-slate-100 transition-all group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.246.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold outfit text-slate-900 mb-3">1. Pengadaan & Distribusi Benih</h3>
                    <p class="text-slate-600 leading-relaxed text-sm mb-6">
                        Memfasilitasi pengadaan benih unggul bersertifikat dari PT. Champ selaku mitra hulu. Koperasi mengelola pengajuan benih dari petani lokal secara teratur guna menjaga kualitas panen kentang.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-500">
                        <li class="flex items-center gap-2">🔹 Pengajuan benih oleh petani via aplikasi</li>
                        <li class="flex items-center gap-2">🔹 Persetujuan pengadaan & validasi stok mitra</li>
                        <li class="flex items-center gap-2">🔹 Pengiriman logistik benih terjadwal</li>
                    </ul>
                </div>

                <!-- Service 2: Petani -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-slate-100 transition-all group">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold outfit text-slate-900 mb-3">2. Manajemen Panen & Harga</h3>
                    <p class="text-slate-600 leading-relaxed text-sm mb-6">
                        Pemberdayaan petani mandiri dalam mencatatkan kegiatan penanaman, hasil panen riil, serta melakukan pengalokasian sisa stok yang akan dijual ke pasar dengan sistem penetapan harga yang transparan.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-500">
                        <li class="flex items-center gap-2">🔹 Pencatatan penanaman & perkiraan panen</li>
                        <li class="flex items-center gap-2">🔹 Pengalokasian stok siap jual ke pasar</li>
                        <li class="flex items-center gap-2">🔹 Fitur tawar-menawar harga dengan koperasi</li>
                    </ul>
                </div>

                <!-- Service 3: Koperasi -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-slate-100 transition-all group">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold outfit text-slate-900 mb-3">3. Pergudangan & Pembelian Koperasi</h3>
                    <p class="text-slate-600 leading-relaxed text-sm mb-6">
                        Koperasi mengumpulkan hasil panen dari berbagai petani lokal secara adil. Sistem pergudangan digital mengotomatiskan pencatatan mutasi stok gudang koperasi demi keamanan rantai suplai pangan.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-500">
                        <li class="flex items-center gap-2">🔹 Pembelian hasil panen langsung dari petani</li>
                        <li class="flex items-center gap-2">🔹 Pencatatan stok gudang terintegrasi</li>
                        <li class="flex items-center gap-2">🔹 Laporan mutasi barang berkala otomatis</li>
                    </ul>
                </div>

                <!-- Service 4: Konsumen -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-slate-100 transition-all group">
                    <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold outfit text-slate-900 mb-3">4. Penjualan Hasil Bumi ke Pasar</h3>
                    <p class="text-slate-600 leading-relaxed text-sm mb-6">
                        Konsumen akhir atau industri pengolahan makanan dapat membeli kentang kualitas premium dengan harga terbaik langsung dari gudang koperasi secara transparan dengan jaminan kesegaran bahan baku.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-500">
                        <li class="flex items-center gap-2">🔹 Katalog produk hasil bumi terlengkap</li>
                        <li class="flex items-center gap-2">🔹 Pembayaran instan aman terverifikasi (Midtrans)</li>
                        <li class="flex items-center gap-2">🔹 Pelacakan pengiriman logistik real-time</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer id="kontak" class="bg-slate-950 py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-12 h-12 flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}?v={{ time() }}" alt="Logo" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <span class="font-bold text-xl outfit text-white">Agroindustri</span>
            </div>
            <p class="text-slate-400 text-sm">© {{ date('Y') }} Smart Agroindustri. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
