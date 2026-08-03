<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Agroindustri</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- PWA Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
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
    @include('partials.welcome-navbar')

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('farm-bg.png') }}?v={{ time() }}" alt="Background" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
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
                        <!-- Dashboard Mockup Image -->
                        <img src="{{ asset('dashboard-preview.png') }}?v={{ time() }}" alt="Dashboard Preview" class="rounded-2xl shadow-2xl border-4 border-white/50 w-full hover:scale-105 transition-transform duration-500 bg-white">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PWA Install Section -->
    <section class="py-12 bg-slate-50 border-y border-slate-100" x-data="pwaInstall()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 rounded-3xl p-8 md:p-10 shadow-xl text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Gradients backdrops -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 space-y-3 max-w-xl text-center md:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/20 text-blue-300 text-xs font-semibold">
                        📱 Aplikasi Web Progresif (PWA)
                    </span>
                    <h2 class="text-2xl font-extrabold outfit">Pasang Aplikasi Agroindustri</h2>
                    <p class="text-slate-300 text-xs leading-relaxed">
                        Akses platform lebih cepat langsung dari layar utama ponsel Anda tanpa perlu mengunduh dari App Store. Lebih ringan, hemat kuota, dan responsif.
                    </p>
                </div>

                <div class="relative z-10 flex flex-col sm:flex-row gap-3 w-full md:w-auto flex-shrink-0 justify-center">
                    <!-- Button Install for Android / Chrome / Windows -->
                    <button @click="installPwa()" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-white text-slate-900 hover:bg-slate-50 font-bold rounded-2xl text-xs transition-all shadow-md gap-2">
                        <span>🤖</span> Pasang untuk Android
                    </button>
                    <!-- Button Install for iOS (Shows Instructions) -->
                    <button @click="showIosModal = true" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-slate-800/80 border border-slate-700 hover:bg-slate-800 text-white font-bold rounded-2xl text-xs transition-all shadow-md gap-2">
                        <span>🍏</span> Petunjuk untuk iOS / Apple
                    </button>
                </div>
            </div>
        </div>

        <!-- iOS Installation Instructions Modal -->
        <div x-show="showIosModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showIosModal = false" class="bg-white rounded-3xl p-8 max-w-sm w-full text-slate-800 relative shadow-2xl border border-slate-100">
                <button @click="showIosModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none text-lg">✕</button>
                
                <div class="text-center space-y-4">
                    <span class="text-4xl">🍏</span>
                    <h3 class="text-xl font-bold outfit text-slate-900">Cara Pasang di iOS (Safari)</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Ikuti langkah mudah berikut untuk menambahkan aplikasi ini ke layar utama iPhone atau iPad Anda:
                    </p>
                </div>

                <ol class="mt-6 space-y-4 text-xs font-semibold text-slate-600 border-t border-slate-100 pt-6">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-[10px]">1</span>
                        <span>Buka halaman ini menggunakan browser <strong>Safari</strong> bawaan iOS.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-[10px]">2</span>
                        <span>Ketuk tombol <strong>Bagikan (Share)</strong> <span class="text-sm">📤</span> pada bagian bawah atau atas layar.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-[10px]">3</span>
                        <span>Pilih menu <strong>"Tambahkan ke Layar Utama" (Add to Home Screen)</strong> <span class="text-sm">➕</span>.</span>
                    </li>
                </ol>

                <button @click="showIosModal = false" class="mt-8 w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition">
                    Mengerti, Siap!
                </button>
            </div>
        </div>

        <!-- Android Installation Instructions Modal -->
        <div x-show="showAndroidModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showAndroidModal = false" class="bg-white rounded-3xl p-8 max-w-sm w-full text-slate-800 relative shadow-2xl border border-slate-100">
                <button @click="showAndroidModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none text-lg">✕</button>
                
                <div class="text-center space-y-4">
                    <span class="text-4xl">🤖</span>
                    <h3 class="text-xl font-bold outfit text-slate-900">Cara Pasang di Android</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Jika tombol pasang otomatis tidak merespons, ikuti langkah mudah berikut di browser Google Chrome ponsel Anda:
                    </p>
                </div>

                <ol class="mt-6 space-y-4 text-xs font-semibold text-slate-600 border-t border-slate-100 pt-6">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-[10px]">1</span>
                        <span>Ketuk tombol <strong>Menu (Tiga Titik)</strong> <span class="text-sm">⋮</span> di pojok kanan atas browser Chrome Anda.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-[10px]">2</span>
                        <span>Pilih menu <strong>"Tambahkan ke Layar Utama" (Add to Home Screen)</strong> atau <strong>"Instal Aplikasi" (Install App)</strong>.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-[10px]">3</span>
                        <span>Ketuk <strong>"Instal" (Install)</strong> untuk mengonfirmasi pemasangan aplikasi.</span>
                    </li>
                </ol>

                <button @click="showAndroidModal = false" class="mt-8 w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition">
                    Mengerti, Siap!
                </button>
            </div>
        </div>
    </section>

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

    <!-- Tentang Kami Section -->
    @include('partials.welcome-tentang')

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

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('ServiceWorker registration successful');
                }).catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

        function pwaInstall() {
            return {
                deferredPrompt: null,
                showIosModal: false,
                showAndroidModal: false,
                init() {
                    window.addEventListener('beforeinstallprompt', (e) => {
                        e.preventDefault();
                        this.deferredPrompt = e;
                    });
                    
                    window.addEventListener('appinstalled', () => {
                        this.deferredPrompt = null;
                        alert('Aplikasi Agroindustri berhasil terpasang di layar utama Anda!');
                    });
                },
                installPwa() {
                    if (this.deferredPrompt) {
                        this.deferredPrompt.prompt();
                        this.deferredPrompt.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === 'accepted') {
                                console.log('User accepted the install prompt');
                            } else {
                                console.log('User dismissed the install prompt');
                            }
                            this.deferredPrompt = null;
                        });
                    } else {
                        // Open the manual installation guide modal for Android
                        this.showAndroidModal = true;
                    }
                }
            }
        }
    </script>
</body>
</html>
