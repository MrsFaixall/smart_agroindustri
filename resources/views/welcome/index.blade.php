<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Agroindustri</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- PWA Tags -->
    <link rel="manifest" href="/manifest.json">
    <script>
        window.deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-ready'));
        });
    </script>
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" href="/icon-192x192.png">
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
    @include('welcome.partials.navbar')

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

    <!-- Features Section --> <!-- Features Section -->
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
    @include('welcome.partials.tentang')

    <!-- Footer CTA & PWA Section -->
    <section class="py-20 bg-slate-900 relative overflow-hidden" x-data="pwaInstall()">
        <!-- Glows matching the CTA section -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-16">
            <!-- CTA Heading Block -->
            <div class="max-w-4xl mx-auto text-center space-y-6">
                <h2 class="text-3xl lg:text-5xl font-bold outfit text-white mb-6">Siap Mentransformasi Bisnis Pertanian Anda?</h2>
                <p class="text-xl text-slate-300 mb-10">Bergabunglah bersama ribuan petani dan koperasi yang telah beralih ke digital.</p>
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-10 py-5 text-lg font-bold text-slate-900 transition-all bg-white rounded-full hover:bg-slate-100 hover:shadow-xl hover:shadow-white/20 hover:-translate-y-1">
                    Buat Akun Gratis
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- PWA Card Block -->
            <div class="bg-slate-950/40 backdrop-blur-md border border-slate-800/80 rounded-3xl p-8 md:p-10 shadow-2xl text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Inner gradients backdrops -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>

                <div class="relative z-10 space-y-3 max-w-xl text-center md:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-semibold">
                        📱 Aplikasi Web Progresif (PWA)
                    </span>
                    <h2 class="text-2xl font-extrabold outfit text-white">Pasang Aplikasi Agroindustri</h2>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Akses platform lebih cepat langsung dari layar utama ponsel Anda. Lebih ringan, hemat kuota, dan responsif.
                    </p>
                </div>

                <div class="relative z-10 flex flex-col sm:flex-row gap-3 w-full md:w-auto flex-shrink-0 justify-center">
                    <button @click="installPwa()" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-white text-slate-900 hover:bg-slate-50 font-bold rounded-2xl text-xs transition-all shadow-md gap-2 uppercase tracking-wider">
                        <span>📥</span> Unduh Sekarang
                    </button>
                </div>
            </div>
        </        <!-- Installation Instructions Modal (Fallback) -->
        <div x-show="showAndroidModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showAndroidModal = false" class="bg-white rounded-3xl p-8 max-w-md w-full text-slate-800 relative shadow-2xl border border-slate-100">
                <button @click="showAndroidModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none text-xl">✕</button>
                
                <div class="text-center space-y-3 mb-6">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto text-3xl">
                        📲
                    </div>
                    <h3 class="text-2xl font-bold outfit text-slate-900">Pasang Aplikasi</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pasang aplikasi **Smart Agroindustri** di layar perangkat Anda untuk akses lebih cepat, responsif, dan hemat kuota.
                    </p>
                </div>

                <div class="space-y-4 border-t border-slate-100 pt-6">
                    <p class="font-bold text-sm text-slate-800 flex items-center gap-2">
                        <span>💡</span> Petunjuk pemasangan manual:
                    </p>

                    <!-- Instruction Card: Insecure Context Warning -->
                    <template x-if="!isSecure">
                        <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 space-y-3 text-xs text-rose-800">
                            <div class="flex items-center gap-2 font-bold text-rose-900">
                                <span>⚠️</span> Koneksi HTTP Tidak Aman
                            </div>
                            <p class="leading-relaxed">
                                Anda mengakses situs ini menggunakan alamat IP jaringan (<span class="font-mono bg-rose-100 px-1 py-0.5 rounded" x-text="window.location.hostname"></span>) tanpa HTTPS.
                            </p>
                            <p class="leading-relaxed font-semibold">
                                Browser seluler (Chrome/Brave) secara ketat memblokir instalasi PWA/APK pada koneksi HTTP non-localhost demi keamanan.
                            </p>
                            <div class="border-t border-rose-200/50 pt-2 space-y-2">
                                <p class="font-bold text-rose-950">Cara memunculkan tombol/pop-up instalasi:</p>
                                <ol class="list-decimal pl-4 space-y-1">
                                    <li>Gunakan **Ngrok** (`ngrok http 8000`) di komputer untuk mendapatkan tautan HTTPS gratis, lalu buka tautan tersebut di HP Anda.</li>
                                    <li>Atau, gunakan fitur **Chrome USB Port Forwarding** agar HP Anda mendeteksi alamat sebagai <span class="font-mono">http://localhost:8000</span> (yang dianggap aman oleh browser).</li>
                                </ol>
                            </div>
                        </div>
                    </template>

                    <!-- Instruction Card: macOS / Windows (Chrome, Brave, Edge) -->
                    <template x-if="isSecure && (deviceInfo.os === 'mac' || deviceInfo.os === 'windows' || deviceInfo.os === 'unknown')">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <span>💻</span> Komputer (Chrome, Brave, Edge, Opera)
                            </div>
                            <ol class="list-decimal pl-4 space-y-2">
                                <li>Cari dan klik ikon <strong>Pasang Aplikasi</strong> (gambar komputer dengan panah bawah <span class="bg-slate-200 px-1 py-0.5 rounded">🖥️📥</span> atau tombol <span class="bg-slate-200 px-1 py-0.5 rounded">➕</span>) di sebelah kanan bilah alamat (URL bar).</li>
                                <li>Klik tombol <strong>Instal</strong> pada jendela konfirmasi.</li>
                                <li>Atau, buka menu browser (titik tiga <span class="font-bold">⋮</span>), pilih <strong>Simpan dan bagikan</strong>, lalu pilih <strong>Instal Halaman sebagai Aplikasi</strong>.</li>
                            </ol>
                        </div>
                    </template>

                    <!-- Instruction Card: macOS Safari -->
                    <template x-if="isSecure && deviceInfo.os === 'mac' && deviceInfo.browser === 'safari'">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <span>🍎</span> Mac (Safari)
                            </div>
                            <ol class="list-decimal pl-4 space-y-2">
                                <li>Klik menu <strong>File</strong> di bagian atas layar Mac Anda.</li>
                                <li>Pilih opsi <strong>Tambahkan ke Dock... (Add to Dock...)</strong>.</li>
                                <li>Klik <strong>Tambah (Add)</strong> untuk menempatkan aplikasi di Dock Anda.</li>
                            </ol>
                        </div>
                    </template>

                    <!-- Instruction Card: Android / Chrome -->
                    <template x-if="isSecure && deviceInfo.os === 'android'">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <span>🤖</span> HP Android (Chrome)
                            </div>
                            <ol class="list-decimal pl-4 space-y-2">
                                <li>Ketuk ikon menu titik tiga (<span class="font-bold">⋮</span>) di pojok kanan atas browser.</li>
                                <li>Pilih menu <strong>Instal Aplikasi</strong> atau <strong>Tambahkan ke Layar Utama</strong>.</li>
                                <li>Ketuk <strong>Instal</strong> untuk mengonfirmasi.</li>
                            </ol>
                        </div>
                    </template>

                    <!-- Instruction Card: iPhone / iPad Safari -->
                    <template x-if="isSecure && deviceInfo.os === 'ios'">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs text-slate-700">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <span>🍏</span> iPhone / iPad (Safari)
                            </div>
                            <ol class="list-decimal pl-4 space-y-2">
                                <li>Ketuk tombol <strong>Bagikan</strong> (ikon kotak dengan panah atas <span class="bg-slate-200 px-1 py-0.5 rounded">📤</span>) di bagian bawah layar.</li>
                                <li>Gulir ke bawah dan ketuk opsi <strong>Tambahkan ke Layar Utama</strong> (Add to Home Screen <span class="bg-slate-200 px-1 py-0.5 rounded">➕</span>).</li>
                                <li>Ketuk <strong>Tambah</strong> di pojok kanan atas.</li>
                            </ol>
                        </div>
                    </template>
                </div>

                <button @click="showAndroidModal = false" class="mt-6 w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl text-xs transition shadow-lg shadow-slate-900/10 uppercase tracking-wider">
                    Mengerti, Siap!
                </button>
            </div>
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
                    registration.update();
                }).catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

        function pwaInstall() {
            return {
                deferredPrompt: window.deferredPrompt,
                showAndroidModal: false,
                deviceInfo: { os: 'unknown', browser: 'unknown' },
                isSecure: window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1',
                init() {
                    this.detectDevice();
                    window.addEventListener('pwa-prompt-ready', () => {
                        this.deferredPrompt = window.deferredPrompt;
                    });
                    
                    window.addEventListener('appinstalled', () => {
                        this.deferredPrompt = null;
                        window.deferredPrompt = null;
                        alert('Aplikasi Agroindustri berhasil terpasang di layar utama Anda!');
                    });
                },
                detectDevice() {
                    const ua = navigator.userAgent.toLowerCase();
                    let os = 'unknown';
                    let browser = 'unknown';

                    if (ua.includes('iphone') || ua.includes('ipad') || ua.includes('ipod')) {
                        os = 'ios';
                    } else if (ua.includes('android')) {
                        os = 'android';
                    } else if (ua.includes('macintosh') || ua.includes('mac os x')) {
                        os = 'mac';
                    } else if (ua.includes('windows')) {
                        os = 'windows';
                    }

                    if (ua.includes('chrome') || ua.includes('crios')) {
                        browser = 'chrome';
                        if (ua.includes('edg')) browser = 'edge';
                    } else if (ua.includes('safari') && !ua.includes('chrome')) {
                        browser = 'safari';
                    } else if (ua.includes('firefox')) {
                        browser = 'firefox';
                    }

                    this.deviceInfo = { os, browser };
                },
                installPwa() {
                    const promptEvent = this.deferredPrompt || window.deferredPrompt;
                    if (promptEvent) {
                        promptEvent.prompt();
                        promptEvent.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === 'accepted') {
                                console.log('User accepted the install prompt');
                            }
                            this.deferredPrompt = null;
                            window.deferredPrompt = null;
                        });
                    } else {
                        // Open the manual installation guide modal
                        this.showAndroidModal = true;
                    }
                }
            }
        }
    </script>
</body>
</html>
