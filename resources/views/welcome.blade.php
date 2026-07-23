<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Agroindustri</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Scripts -->
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
        .hero-bg {
            background: radial-gradient(circle at top right, #e0f2fe 0%, #f0fdf4 50%, #ffffff 100%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-800">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-2xl outfit tracking-tight text-slate-900">Agro<span class="text-blue-600">industri</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">Fitur</a>
                    <a href="#tentang" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">Tentang Kami</a>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white transition-all bg-blue-600 rounded-full hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-0.5">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-semibold mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        Platform Rantai Pasok Digital
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 outfit leading-tight">
                        Pertanian Cerdas <br>Untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-500">Masa Depan</span>
                    </h1>
                    <p class="text-lg text-slate-600 mb-10 leading-relaxed max-w-xl">
                        Hubungkan petani, koperasi, dan pasar dalam satu ekosistem digital terintegrasi. Tingkatkan efisiensi logistik, transparansi harga, dan kesejahteraan bersama.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-white transition-all bg-slate-900 rounded-full hover:bg-slate-800 hover:shadow-xl hover:shadow-slate-200 hover:-translate-y-1">
                            Mulai Sekarang
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="#fitur" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-slate-700 transition-all bg-white border-2 border-slate-200 rounded-full hover:border-slate-300 hover:bg-slate-50">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="relative lg:h-[600px] flex items-center justify-center">
                    <!-- Abstract decorative elements -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-100 to-emerald-50 rounded-full blur-3xl opacity-50 animate-pulse"></div>
                    <div class="relative w-full max-w-lg animate-float">
                        <!-- Dashboard Mockup -->
                        <div class="bg-white/80 backdrop-blur-xl border border-white shadow-2xl rounded-2xl p-4 overflow-hidden relative">
                            <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="h-8 w-1/3 bg-slate-100 rounded-lg"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="h-24 bg-blue-50 rounded-xl border border-blue-100 p-4">
                                        <div class="h-4 w-1/2 bg-blue-200 rounded mb-2"></div>
                                        <div class="h-6 w-3/4 bg-blue-300 rounded"></div>
                                    </div>
                                    <div class="h-24 bg-emerald-50 rounded-xl border border-emerald-100 p-4">
                                        <div class="h-4 w-1/2 bg-emerald-200 rounded mb-2"></div>
                                        <div class="h-6 w-3/4 bg-emerald-300 rounded"></div>
                                    </div>
                                </div>
                                <div class="h-32 bg-slate-50 rounded-xl border border-slate-100"></div>
                            </div>
                            
                            <!-- Floating small card -->
                            <div class="absolute -right-6 -bottom-6 bg-white p-4 rounded-xl shadow-xl border border-slate-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase">Transaksi Berhasil</p>
                                    <p class="text-sm font-bold text-slate-800">Rp 12.500.000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold outfit text-slate-900 mb-4">Mengapa Memilih Smart Agroindustri?</h2>
                <p class="text-lg text-slate-600">Kami menghadirkan solusi digital komprehensif untuk menyederhanakan kompleksitas rantai pasok pertanian Anda.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 border border-transparent hover:border-slate-100 group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold outfit text-slate-900 mb-3">Transparansi Data</h3>
                    <p class="text-slate-600 leading-relaxed">Pantau pergerakan stok, harga pasar, dan status transaksi secara real-time. Tidak ada lagi informasi yang tertutup.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 border border-transparent hover:border-slate-100 group">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold outfit text-slate-900 mb-3">Pembayaran Terintegrasi</h3>
                    <p class="text-slate-600 leading-relaxed">Terima dan kirim pembayaran dengan aman melalui integrasi Midtrans. Mendukung QRIS, Virtual Account, dan E-Wallet.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 border border-transparent hover:border-slate-100 group">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold outfit text-slate-900 mb-3">Efisiensi Logistik</h3>
                    <p class="text-slate-600 leading-relaxed">Kelola manajemen pergudangan, panen, dan distribusi dengan sistem yang mengotomatiskan pencatatan stok Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-900"></div>
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h2 class="text-3xl lg:text-5xl font-bold outfit text-white mb-6">Siap Mentransformasi Bisnis Pertanian Anda?</h2>
            <p class="text-xl text-slate-300 mb-10">Bergabunglah bersama ribuan petani dan koperasi yang telah beralih ke digital.</p>
            <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-10 py-5 text-lg font-bold text-slate-900 transition-all bg-white rounded-full hover:bg-slate-100 hover:shadow-xl hover:shadow-white/20 hover:-translate-y-1">
                Buat Akun Gratis
                <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    <!-- Simple Footer -->
    <footer class="bg-slate-950 py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-bold text-xl outfit text-white">Agroindustri</span>
            </div>
            <p class="text-slate-400 text-sm">© {{ date('Y') }} Smart Agroindustri. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
