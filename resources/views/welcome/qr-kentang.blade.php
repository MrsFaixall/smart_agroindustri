<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smartagro - Pelacakan QR & Rute Cerdas Kentang</title>
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
    <script src="https://unpkg.com/html5-qrcode"></script>
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
            background: radial-gradient(circle at top right, #fffbeb 0%, #f0fdf4 50%, #ffffff 100%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        @keyframes scan {
            0%, 100% { top: 0%; opacity: 0.8; }
            50% { top: 100%; opacity: 0.8; }
        }
        .scanner-line {
            animation: scan 2.5s ease-in-out infinite;
        }
        @keyframes route-glow {
            0% { stroke-dashoffset: 24; }
            100% { stroke-dashoffset: 0; }
        }
        .animate-route {
            stroke-dasharray: 8 4;
            animation: route-glow 2s linear infinite;
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-800">

    <!-- Navbar -->
    @include('welcome.partials.navbar')

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-bg">
        <div class="absolute inset-0 z-0">
            <!-- Subtle potato farm pattern background -->
            <div class="absolute inset-0 bg-[radial-gradient(#10b981_0.8px,transparent_0.8px)] [background-size:24px_24px] opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/80 to-white"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Platform Rantai Pasok Kentang Cerdas
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 outfit leading-tight">
                        Masa Depan <br>Pertanian Kentang <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-amber-500">Transparan & Efisien</span>
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 mb-10 leading-relaxed max-w-xl">
                        Smartagro mendigitalisasi koperasi tani kentang secara menyeluruh. Dari teknologi <strong>Smart QR Batching</strong> untuk keterbukaan mutu, hingga <strong>Smart Routing Delivery</strong> untuk rute pengiriman logistik tercepat.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#demo-pindai" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-white transition-all bg-emerald-600 rounded-full hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 hover:-translate-y-1">
                            Coba Demo Pindai
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-4v-4m-4 4H5m6 0v-4m0 4v4m4-4h2m-6-6h2m-2 0H5v-4m0 4v4m4-4h2m-2-6V3"></path></svg>
                        </a>
                        <a href="#fitur" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-slate-700 transition-all bg-white border-2 border-slate-200 rounded-full hover:border-slate-300 hover:bg-slate-50">
                            Jelajahi Fitur
                        </a>
                    </div>
                </div>
                <div class="relative lg:h-[600px] flex items-center justify-center">
                    <!-- abstract glow -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100 to-amber-50 rounded-full blur-3xl opacity-60 animate-pulse"></div>
                    
                    <div class="relative w-full max-w-md animate-float">
                        <!-- Custom CSS/HTML Phone Mockup representing Smartagro -->
                        <div class="relative mx-auto border-slate-900 bg-slate-900 border-[12px] rounded-[2.5rem] h-[520px] w-[270px] sm:w-[290px] shadow-2xl overflow-hidden ring-4 ring-slate-100">
                            <!-- Notch -->
                            <div class="absolute top-0 inset-x-0 h-4 bg-slate-900 rounded-b-xl flex justify-center z-30">
                                <div class="w-16 h-2.5 bg-slate-950 rounded-full mt-0.5"></div>
                            </div>
                            
                            <!-- Screen Content -->
                            <div class="relative h-full bg-slate-50 flex flex-col justify-between pt-6 px-4 pb-4 overflow-hidden">
                                <!-- App Header -->
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 mb-2">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded bg-emerald-500 flex items-center justify-center text-white text-[9px] font-bold">SA</div>
                                        <span class="text-[10px] font-bold text-slate-800">Smartagro App</span>
                                    </div>
                                    <span class="text-[8px] px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Online</span>
                                </div>
                                
                                <!-- Scanner Mock -->
                                <div class="relative flex-1 bg-slate-950 rounded-xl overflow-hidden flex flex-col items-center justify-center p-3 mb-2.5 border border-slate-800">
                                    <!-- Laser line -->
                                    <div class="absolute left-0 right-0 h-0.5 bg-emerald-500 shadow-[0_0_8px_#10b981] scanner-line z-20"></div>
                                    
                                    <!-- Target corners -->
                                    <div class="absolute inset-4 border border-emerald-500/10 rounded-lg flex items-center justify-center">
                                        <div class="absolute top-0 left-0 w-2 h-2 border-t-2 border-l-2 border-emerald-500"></div>
                                        <div class="absolute top-0 right-0 w-2 h-2 border-t-2 border-r-2 border-emerald-500"></div>
                                        <div class="absolute bottom-0 left-0 w-2 h-2 border-b-2 border-l-2 border-emerald-500"></div>
                                        <div class="absolute bottom-0 right-0 w-2 h-2 border-b-2 border-r-2 border-emerald-500"></div>
                                        
                                        <!-- QR Symbol -->
                                        <svg class="w-12 h-12 text-emerald-400/80 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm13-2h3v2h-3v-2zm3 3h3v3h-3v-3zm-3 3h3v-3h-3v3zm-3-3h3v3h-3v-3zm3-3h-3v3h3v-3zm-6-2v3h-3v2h5v-5h-2zm4 4v3h2v-3h-2zm-2 2h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    
                                    <span class="absolute bottom-2 text-[8px] text-emerald-400 font-mono tracking-widest animate-pulse">PINDAI BATCH KENTANG</span>
                                </div>

                                <!-- Result Card Mock -->
                                <div class="bg-white border border-slate-100 rounded-xl p-2.5 shadow-md">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[8px] font-bold text-emerald-600 uppercase">Terverifikasi</span>
                                        <span class="text-[8px] bg-slate-100 text-slate-600 px-1 py-0.5 rounded font-mono font-semibold">#KTG-0824</span>
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-800 mb-1">Kentang Granola Super (Grade A)</div>
                                    
                                    <div class="w-full bg-slate-100 h-1 rounded overflow-hidden mb-2">
                                        <div class="bg-emerald-500 h-full w-[90%]"></div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-[7px] text-slate-400 border-t border-slate-50 pt-1">
                                        <span>Dieng, Wonosobo</span>
                                        <span class="text-indigo-600 font-bold">Rute Cepat Cerdas</span>
                                    </div>
                                </div>
                                
                                <!-- Home Indicator -->
                                <div class="w-20 h-1 bg-slate-200 rounded-full mx-auto mt-1 flex-shrink-0"></div>
                            </div>
                        </div>

                        <!-- Floating Indicators -->
                        <div class="absolute -right-6 top-1/4 bg-white rounded-xl shadow-lg p-2.5 border border-slate-100 flex items-center gap-2 max-w-[120px] animate-float" style="animation-delay: 1.5s;">
                            <div class="w-6 h-6 rounded bg-amber-100 text-amber-600 flex items-center justify-center text-xs">🥔</div>
                            <div>
                                <div class="text-[7px] text-slate-400 font-bold uppercase">Varietas</div>
                                <div class="text-[9px] font-extrabold text-slate-800">Granola Super</div>
                            </div>
                        </div>
                        
                        <div class="absolute -left-6 bottom-1/4 bg-white rounded-xl shadow-lg p-2.5 border border-slate-100 flex items-center gap-2 max-w-[120px] animate-float" style="animation-delay: 3s;">
                            <div class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">🚚</div>
                            <div>
                                <div class="text-[7px] text-slate-400 font-bold uppercase">Rute Tercepat</div>
                                <div class="text-[9px] font-extrabold text-slate-800">Bebas Macet</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold uppercase tracking-wider mb-4 outfit">
                    Fitur Utama Platform
                </div>
                <h2 class="text-3xl lg:text-5xl font-bold outfit text-slate-900 mb-4 tracking-tight">Sistem Rantai Pasok Kentang Terintegrasi</h2>
                <p class="text-lg text-slate-600">Smartagro menyediakan solusi digital ujung-ke-ujung (end-to-end) yang dirancang khusus untuk meningkatkan efisiensi dan nilai jual komoditas kentang.</p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Feature 1: Smart QR Batching -->
                <div class="p-8 sm:p-10 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 border border-slate-100 group flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-4v-4m-4 4H5m6 0v-4m0 4v4m4-4h2m-6-6h2m-2 0H5v-4m0 4v4m4-4h2m-2-6V3"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold outfit text-slate-900 mb-3">Smart QR Batching</h3>
                            <p class="text-slate-600 leading-relaxed text-sm sm:text-base">
                                Setiap karung kentang mendapatkan identitas unik melalui QR Code batch yang dicetak koperasi. QR Code ini melacak data kuantitas (berat), jenis kentang (seperti Granola atau Atlantic), dan grade kualitas (A, B, C) langsung dari petani. Konsumen publik dapat memindai tanpa perlu login untuk melihat transparansi asal-usul.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Visual Mockup for QR Code Batching inside feature card -->
                    <div class="mt-8 pt-6 border-t border-slate-200/60 relative overflow-hidden bg-white rounded-2xl p-4 flex items-center justify-between gap-4 border border-slate-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <!-- QR Code SVG -->
                            <div class="p-2 border border-slate-100 rounded-xl bg-slate-50 group-hover:border-emerald-200 transition-colors">
                                <svg class="w-16 h-16 text-slate-800" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm14-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm13-2h3v2h-3v-2zm3 3h3v3h-3v-3zm-3 3h3v-3h-3v3zm-3-3h3v3h-3v-3zm3-3h-3v3h3v-3zm-6-2v3h-3v2h5v-5h-2zm4 4v3h2v-3h-2zm-2 2h2v2h-2v-2z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-600">E-Label Terverifikasi</span>
                                <div class="text-xs font-bold text-slate-800 mt-0.5">Batch #KTG-0824-A</div>
                                <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap gap-x-2">
                                    <span>🥔 Granola Super</span>
                                    <span>•</span>
                                    <span class="text-emerald-700 font-semibold bg-emerald-50 px-1 rounded">Grade A</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden sm:flex flex-col items-end text-right">
                            <span class="text-[9px] text-slate-400">Dibuat Koperasi</span>
                            <span class="text-xs font-bold text-slate-700">08 Agu 2026</span>
                            <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-full mt-1">50 Kg</span>
                        </div>
                    </div>
                </div>

                <!-- Feature 2: Smart Routing Delivery -->
                <div class="p-8 sm:p-10 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 border border-slate-100 group flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold outfit text-slate-900 mb-3">Smart Routing Delivery</h3>
                            <p class="text-slate-600 leading-relaxed text-sm sm:text-base">
                                Menghubungkan koperasi dengan rute peta terintegrasi untuk merekomendasikan pengiriman kentang terbaik ke pasar atau konsumen akhir. Algoritma kami memperhitungkan kondisi kemacetan, jarak, dan efisiensi bahan bakar secara real-time, merekomendasikan rute paling cepat untuk mencegah penyusutan berat kentang.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Visual Mockup for Smart Routing Maps inside feature card -->
                    <div class="mt-8 pt-6 border-t border-slate-200/60 relative overflow-hidden bg-white rounded-2xl p-3 border border-slate-100 shadow-sm flex flex-col gap-2">
                        <div class="flex justify-between items-center text-xs">
                            <div class="flex items-center gap-1.5 text-slate-800 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Rute Logistik Optimal</span>
                            </div>
                            <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">Lebih Cepat 45 Menit</span>
                        </div>
                        <!-- Mini Map Path visual -->
                        <div class="bg-slate-50 border border-slate-100 rounded-xl h-24 relative overflow-hidden">
                            <svg class="w-full h-full" viewBox="0 0 300 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Paths -->
                                <path d="M20 70 L90 70 L140 30 L280 30" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round"/>
                                <path d="M20 70 Q 100 80 150 40 T 280 30" stroke="#6366f1" stroke-width="4" stroke-linecap="round" stroke-dasharray="6 4" class="animate-route"/>
                                <path d="M20 70 Q 100 80 150 40 T 280 30" stroke="#10b981" stroke-width="2" stroke-linecap="round"/>
                                
                                <!-- Markers -->
                                <circle cx="20" cy="70" r="4" fill="#f59e0b"/>
                                <circle cx="280" cy="30" r="4" fill="#ef4444"/>
                                
                                <!-- Truck Marker -->
                                <g transform="translate(130, 48)">
                                    <rect width="16" height="8" rx="2" fill="#1e293b"/>
                                    <circle cx="4" cy="8" r="2" fill="#64748b"/>
                                    <circle cx="12" cy="8" r="2" fill="#64748b"/>
                                </g>
                            </svg>
                            <span class="absolute bottom-2 left-2 text-[9px] font-mono text-slate-400">DIENG &rarr; JAKARTA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="alur-kerja" class="py-24 bg-slate-50 relative overflow-hidden">
        <!-- Background accents -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[300px] bg-gradient-to-b from-emerald-50/30 to-transparent rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-700 text-xs font-bold uppercase tracking-wider mb-4 outfit">
                    Alur Kerja
                </div>
                <h2 class="text-3xl lg:text-5xl font-bold outfit text-slate-900 mb-4 tracking-tight">3 Langkah Mudah Pelacakan</h2>
                <p class="text-lg text-slate-600">Bagaimana Smartagro menjamin transparansi kualitas kentang dari lahan tani hingga ke tangan Anda.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-12 relative">
                <!-- Decorative Connector Line for Desktop -->
                <div class="hidden md:block absolute top-1/2 left-[15%] right-[15%] h-0.5 border-t border-dashed border-slate-300 -translate-y-12 -z-10"></div>
                
                <!-- Step 1 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-100/50 relative hover:-translate-y-2 transition-transform duration-300">
                    <div class="absolute -top-6 left-8 w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-lg shadow-lg shadow-emerald-200">
                        1
                    </div>
                    <div class="mt-4 space-y-4">
                        <div class="text-4xl flex items-center justify-center w-12 h-12 bg-emerald-50 rounded-2xl">🌾</div>
                        <h3 class="text-xl font-bold outfit text-slate-900">Koperasi Input Data</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Petani membawa hasil panen ke koperasi. Petugas koperasi menimbang, menyortir, dan memasukkan data (varietas kentang, berat, tanggal panen, & grade kualitas) ke aplikasi Smartagro.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-100/50 relative hover:-translate-y-2 transition-transform duration-300">
                    <div class="absolute -top-6 left-8 w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-extrabold text-lg shadow-lg shadow-amber-200">
                        2
                    </div>
                    <div class="mt-4 space-y-4">
                        <div class="text-4xl flex items-center justify-center w-12 h-12 bg-amber-50 rounded-2xl">🏷️</div>
                        <h3 class="text-xl font-bold outfit text-slate-900">Sistem Cetak Smart QR</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Sistem secara otomatis membuat QR Code unik berbasis batch pengiriman. Koperasi mencetak label tersebut dan menempelkannya langsung pada karung kentang.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-100/50 relative hover:-translate-y-2 transition-transform duration-300">
                    <div class="absolute -top-6 left-8 w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-extrabold text-lg shadow-lg shadow-indigo-200">
                        3
                    </div>
                    <div class="mt-4 space-y-4">
                        <div class="text-4xl flex items-center justify-center w-12 h-12 bg-indigo-50 rounded-2xl">📱</div>
                        <h3 class="text-xl font-bold outfit text-slate-900">Konsumen Scan Publik</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Pembeli grosir atau konsumen akhir memindai QR Code di karung kentang menggunakan kamera HP. Rincian data asal usul dan kualitas kentang langsung terlihat tanpa login.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Demo Section -->
    <section id="demo-pindai" class="py-24 bg-white relative overflow-hidden" x-data="qrScanner()">
        <!-- Decorative blobs -->
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-emerald-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-4 outfit">
                    Uji Demo Interaktif
                </div>
                <h2 class="text-3xl lg:text-5xl font-bold outfit text-slate-900 mb-4 tracking-tight">Simulasikan Pindai QR Kentang</h2>
                <p class="text-lg text-slate-600">Klik salah satu batch contoh di bawah, lalu jalankan simulator untuk melihat informasi transparansi kualitas yang didapatkan konsumen secara real-time.</p>
            </div>

            <div class="grid lg:grid-cols-12 gap-12 items-stretch">
                <!-- Left: Smartphone QR Scanner Widget (5 Cols) -->
                <div class="lg:col-span-5 flex flex-col items-center justify-start space-y-6 bg-slate-50 border border-slate-100 rounded-3xl p-6 sm:p-8">
                    <div class="w-full text-center sm:text-left">
                        <h3 class="text-xl font-bold outfit text-slate-900">Pemindai QR Cerdas</h3>
                        <p class="text-xs text-slate-500 mt-1">Arahkan kamera ke QR Code tag karung kentang, unggah gambar, atau pilih salah satu batch simulasi di bawah.</p>
                    </div>

                    <!-- Smartphone Frame -->
                    <div class="relative w-full max-w-[280px] sm:max-w-[290px] bg-slate-900 p-3.5 rounded-[42px] shadow-2xl border-4 border-slate-700 shadow-indigo-500/10">
                        <!-- Ear Speaker & Notch -->
                        <div class="absolute top-4 left-1/2 -translate-x-1/2 w-28 h-4.5 bg-slate-900 rounded-b-2xl z-40 flex items-center justify-center gap-1.5 border-x border-b border-slate-800">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-800"></div>
                            <div class="w-10 h-0.5 bg-slate-800 rounded-full"></div>
                        </div>
                        
                        <!-- Smartphone Screen -->
                        <div class="relative rounded-[32px] overflow-hidden aspect-[9/16] bg-slate-950 flex flex-col justify-between border border-slate-800">
                            
                            <!-- Scanner Screen Area -->
                            <div class="relative flex-1 bg-slate-950 flex flex-col items-center justify-center overflow-hidden">
                                
                                <!-- Scanning Laser Line Animation -->
                                <div x-show="isRealCameraActive || isScanning" class="absolute left-0 right-0 h-1 bg-emerald-500 shadow-[0_0_15px_#10b981] scanner-line z-30 pointer-events-none"></div>
                                
                                <!-- Real Device Camera Viewport -->
                                <div id="interactive-camera" class="absolute inset-0 w-full h-full object-cover bg-slate-900 transition-opacity duration-300" :class="isRealCameraActive ? 'opacity-100 z-10' : 'opacity-0 -z-10'"></div>
                                
                                <!-- Simulated Scanner Animation Viewport -->
                                <div x-show="isScanning" class="absolute inset-0 z-10 bg-slate-950 flex items-center justify-center">
                                    <div class="absolute inset-6 border border-emerald-500/30 rounded-xl flex items-center justify-center">
                                        <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-emerald-500"></div>
                                        <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-emerald-500"></div>
                                        <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-emerald-500"></div>
                                        <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-emerald-500"></div>
                                        <svg class="w-12 h-12 text-emerald-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm14-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm13-2h3v2h-3v-2zm3 3h3v3h-3v-3zm-3 3h3v-3h-3v3zm-3-3h3v3h-3v-3zm3-3h-3v3h3v-3zm-6-2v3h-3v2h5v-5h-2zm4 4v3h2v-3h-2zm-2 2h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-6 left-0 right-0 text-center z-20">
                                        <span class="text-[9px] font-mono tracking-widest text-emerald-400 animate-pulse uppercase">Memindai Batch...</span>
                                    </div>
                                </div>
                                
                                <!-- Standby Screen (Inactive state) -->
                                <div x-show="!isRealCameraActive && !isScanning && !scanCompleted" class="p-6 text-center space-y-4 z-20 text-white select-none">
                                    <div class="w-14 h-14 bg-slate-900 border border-slate-800 rounded-full flex items-center justify-center text-2xl mx-auto shadow-inner animate-pulse">
                                        📷
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold outfit">Kamera Standby</h4>
                                        <p class="text-[9px] text-slate-400 mt-1 max-w-[180px] mx-auto">Aktifkan kamera ponsel atau pilih salah satu opsi pemicu di bawah.</p>
                                    </div>
                                </div>
                                
                                <!-- Scan Completed Success Overlay -->
                                <div x-show="scanCompleted" class="absolute inset-0 bg-emerald-950/95 backdrop-blur-md flex flex-col items-center justify-center z-40 space-y-2 text-center p-4">
                                    <span class="text-3xl animate-bounce">🎉</span>
                                    <h4 class="text-white text-[10px] font-bold uppercase tracking-widest font-mono">Pindai Berhasil!</h4>
                                    <p class="text-[8px] text-emerald-300 font-mono break-all max-w-[160px]" x-text="'ID: ' + batches[selectedBatch].id"></p>
                                    <button @click="scanCompleted = false; showResult = false;" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[9px] font-bold shadow transition-all mt-2">Ulangi</button>
                                </div>

                                <!-- Error Alert Overlay -->
                                <div x-show="errorMessage !== ''" class="absolute bottom-16 left-3 right-3 bg-rose-950/90 border border-rose-900 text-rose-200 text-[8px] p-2 rounded-xl z-30 text-center backdrop-blur-sm">
                                    <p x-text="errorMessage"></p>
                                </div>
                            </div>
                            
                            <!-- Phone Screen Bottom Control Panel -->
                            <div class="bg-slate-900/95 border-t border-slate-800/80 p-3 z-20 flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <!-- Toggle Live Camera Button -->
                                    <button @click="isRealCameraActive ? stopRealCamera() : startRealCamera()" 
                                            class="flex-1 py-2 px-3 rounded-xl font-bold text-[9px] text-white transition flex items-center justify-center gap-1.5 shadow"
                                            :class="isRealCameraActive ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700'">
                                        <span x-show="!isRealCameraActive">🟢 Mulai Kamera</span>
                                        <span x-show="isRealCameraActive">🔴 Stop Kamera</span>
                                    </button>
                                    
                                    <!-- Flip Camera (Toggle front/back facing mode) -->
                                    <button @click="flipCamera()" 
                                            :disabled="!isRealCameraActive"
                                            class="p-2 bg-slate-800 hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl transition flex items-center justify-center flex-shrink-0"
                                            title="Putar Arah Kamera">
                                        🔄
                                    </button>
                                </div>
                                
                                <!-- Fallback upload file -->
                                <label class="w-full py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl text-[8px] text-center cursor-pointer transition flex items-center justify-center gap-1">
                                    📁 Unggah Gambar QR
                                    <input type="file" @change="handleImageUpload($event)" class="hidden">
                                </label>
                            </div>
                        </div>
                        
                        <!-- Phone bottom home indicator -->
                        <div class="w-16 h-1 bg-slate-700 rounded-full mx-auto mt-2"></div>
                    </div>

                    <!-- Batch & Token triggers (Unification) -->
                    <div class="w-full pt-4 border-t border-slate-200/60 space-y-4">
                        <div class="text-center sm:text-left">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Pemicu Simulasi Batch</h4>
                            <p class="text-[10px] text-slate-400">Pilih salah satu batch kentang untuk disimulasikan pindaiannya pada ponsel di atas.</p>
                        </div>
                        
                        <!-- Simulated triggers list -->
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="selectedBatch = 'batch-1'; isRealCameraActive = false; scanCompleted = false; showResult = false; isScanning = true; setTimeout(() => { isScanning = false; scanCompleted = true; showResult = true; playBeep(); }, 1500);" 
                                    class="p-2 rounded-xl border text-center transition-all bg-white hover:border-emerald-500"
                                    :class="selectedBatch === 'batch-1' ? 'border-emerald-500 ring-2 ring-emerald-50' : 'border-slate-200'">
                                <span class="text-base block mb-0.5">🥔</span>
                                <span class="text-[9px] font-bold text-slate-700 block">Granola</span>
                                <span class="text-[8px] text-slate-400 font-mono">Grade A</span>
                            </button>

                            <button @click="selectedBatch = 'batch-2'; isRealCameraActive = false; scanCompleted = false; showResult = false; isScanning = true; setTimeout(() => { isScanning = false; scanCompleted = true; showResult = true; playBeep(); }, 1500);" 
                                    class="p-2 rounded-xl border text-center transition-all bg-white hover:border-emerald-500"
                                    :class="selectedBatch === 'batch-2' ? 'border-emerald-500 ring-2 ring-emerald-50' : 'border-slate-200'">
                                <span class="text-base block mb-0.5">🥔</span>
                                <span class="text-[9px] font-bold text-slate-700 block">Atlantic</span>
                                <span class="text-[8px] text-slate-400 font-mono">Grade B</span>
                            </button>

                            <button @click="selectedBatch = 'batch-3'; isRealCameraActive = false; scanCompleted = false; showResult = false; isScanning = true; setTimeout(() => { isScanning = false; scanCompleted = true; showResult = true; playBeep(); }, 1500);" 
                                    class="p-2 rounded-xl border text-center transition-all bg-white hover:border-emerald-500"
                                    :class="selectedBatch === 'batch-3' ? 'border-emerald-500 ring-2 ring-emerald-50' : 'border-slate-200'">
                                <span class="text-base block mb-0.5">🥔</span>
                                <span class="text-[9px] font-bold text-slate-700 block">Merah</span>
                                <span class="text-[8px] text-slate-400 font-mono">Grade A+</span>
                            </button>
                        </div>

                        <!-- Manual Token Input Form -->
                        <form @submit.prevent="if (searchToken.trim() === '') { alert('Mohon masukkan token QR Anda!'); return; } handleScanSuccess(searchToken.trim())" class="relative flex items-center rounded-2xl border border-slate-200 bg-white p-1 focus-within:border-emerald-500 transition-all">
                            <input type="text" x-model="searchToken" placeholder="Masukkan token UUID..." class="flex-1 pl-3 text-[10px] font-mono font-bold outline-none text-slate-700 placeholder-slate-400 bg-transparent">
                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-3 py-1.5 rounded-xl text-[9px] transition">🔍 Cari</button>
                        </form>
                    </div>
                </div>

                <!-- Right: Scan Result Presentation Card (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col justify-center min-h-[500px] overflow-hidden relative">
                    
                    <!-- Pre-scan / Idle State -->
                    <div x-show="!showResult && !isScanning" class="p-8 sm:p-12 text-center space-y-4">
                        <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-4xl mx-auto border border-slate-100 shadow-sm">
                            📱
                        </div>
                        <h3 class="text-2xl font-bold outfit text-slate-800">Menunggu Pemindaian</h3>
                        <p class="text-slate-500 max-w-sm mx-auto text-sm">
                            Gunakan tombol pemindai di sebelah kiri untuk menyimulasikan pembeli men-scan label QR kentang.
                        </p>
                    </div>

                    <!-- Scanning State loader -->
                    <div x-show="isScanning" class="p-8 text-center space-y-4" style="display: none;">
                        <svg class="animate-spin h-10 w-10 text-emerald-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <h3 class="text-lg font-bold outfit text-slate-700">Menerima Data Batch Kentang...</h3>
                        <p class="text-slate-400 text-xs font-mono" x-text="'ID: ' + batches[selectedBatch].id"></p>
                    </div>

                    <!-- Result Completed state (Main Card) -->
                    <div x-show="showResult" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-6"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="flex flex-col h-full"
                         style="display: none;">
                        
                        <!-- Card Header Banner -->
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 p-6 text-white relative">
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full border border-white/20 flex items-center gap-1.5 uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span> Publik (Tanpa Login)
                            </div>
                            <span class="text-[10px] font-mono tracking-widest text-emerald-100 uppercase" x-text="'BATCH ID: ' + batches[selectedBatch].id"></span>
                            <h4 class="text-2xl font-extrabold outfit mt-1" x-text="batches[selectedBatch].varietas"></h4>
                            <p class="text-xs text-emerald-100 mt-1" x-text="batches[selectedBatch].koperasi + ' • ' + batches[selectedBatch].lokasi"></p>
                        </div>
                        
                        <!-- Card Content Grid -->
                        <div class="p-6 sm:p-8 space-y-6 flex-1 bg-white">
                            
                            <!-- Key Metrics Rows -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Grade Mutu</span>
                                    <span class="text-sm sm:text-base font-extrabold text-slate-800" x-text="batches[selectedBatch].grade.split(' ')[0] + ' ' + batches[selectedBatch].grade.split(' ')[1]"></span>
                                </div>
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Kuantitas</span>
                                    <span class="text-sm sm:text-base font-extrabold text-slate-800" x-text="batches[selectedBatch].berat"></span>
                                </div>
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Ketinggian</span>
                                    <span class="text-sm sm:text-base font-extrabold text-slate-800" x-text="batches[selectedBatch].ketinggian"></span>
                                </div>
                            </div>
                            
                            <!-- Farmer and Origin Details -->
                            <div class="border-t border-slate-100 pt-6">
                                <h5 class="text-xs font-bold text-slate-400 uppercase mb-3">Informasi Asal & Kualitas</h5>
                                <div class="grid sm:grid-cols-2 gap-4 text-sm text-slate-700">
                                    <div class="space-y-2">
                                        <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-50 pb-1.5 sm:pb-0">
                                            <span class="text-slate-400 w-28 flex-shrink-0">Nama Petani</span>
                                            <span class="font-bold text-slate-800 text-right sm:text-left truncate" x-text="batches[selectedBatch].petani.split(' - ')[0]"></span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-50 pb-1.5 sm:pb-0">
                                            <span class="text-slate-400 w-28 flex-shrink-0">Kelompok Tani</span>
                                            <span class="font-semibold text-slate-800 text-right sm:text-left truncate" x-text="batches[selectedBatch].petani.split(' - ')[1] || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between sm:justify-start sm:gap-6 border-b sm:border-0 border-slate-50 pb-1.5 sm:pb-0">
                                            <span class="text-slate-400 w-28 flex-shrink-0">Tanggal Panen</span>
                                            <span class="font-bold text-slate-800 text-right sm:text-left" x-text="batches[selectedBatch].tanggalPanen"></span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start sm:gap-6">
                                            <span class="text-slate-400 w-28 flex-shrink-0">Status Uji</span>
                                            <span class="font-bold text-emerald-600 text-right sm:text-left">Lolos Mutu & Organik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Logistics & Routing -->
                            <div class="border-t border-slate-100 pt-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <h5 class="text-xs font-bold text-slate-400 uppercase">Pelacakan Smart Routing Delivery</h5>
                                    <span class="text-[10px] text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-bold" x-text="batches[selectedBatch].truckNo"></span>
                                </div>
                                
                                <div class="grid sm:grid-cols-2 gap-4 text-sm text-slate-700 bg-slate-50/50 rounded-2xl p-4 border border-slate-100/50">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Tujuan</span>
                                            <span class="font-bold text-slate-800 text-right" x-text="batches[selectedBatch].tujuan"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Jarak Tempuh</span>
                                            <span class="font-semibold text-slate-800" x-text="batches[selectedBatch].jarak"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Waktu Perjalanan</span>
                                            <span class="font-bold text-indigo-600" x-text="batches[selectedBatch].waktuTempuh"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">Suhu Truk Kargo</span>
                                            <span class="font-semibold text-slate-800" x-text="batches[selectedBatch].suhuKargo"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic SVG map representing the route -->
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl h-36 overflow-hidden relative" x-html="batches[selectedBatch] ? batches[selectedBatch].mapSvg : ''">
                                </div>
                                <p class="text-[10px] text-slate-400 text-center leading-relaxed font-semibold italic">
                                    "Optimasi rute Smart Routing menjamin kentang sampai di tujuan dalam kesegaran maksimal."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    @include('welcome.partials.tentang')

    <!-- Footer CTA & PWA Section -->
    <section class="py-20 bg-slate-900 relative overflow-hidden" x-data="pwaInstall()">
        <!-- Glows matching the CTA section -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-amber-500 rounded-full blur-3xl opacity-20"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-16">
            <!-- CTA Heading Block -->
            <div class="max-w-4xl mx-auto text-center space-y-6">
                <h2 class="text-3xl lg:text-5xl font-bold outfit text-white mb-6">Siap Mentransformasi Rantai Pasok Kentang Anda?</h2>
                <p class="text-xl text-slate-300 mb-10">Bergabunglah bersama ribuan petani dan koperasi yang telah beralih ke digital.</p>
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-10 py-5 text-lg font-bold text-slate-900 transition-all bg-white rounded-full hover:bg-slate-100 hover:shadow-xl hover:shadow-white/20 hover:-translate-y-1">
                    Buat Akun Gratis
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- PWA Card Block -->
            <div class="bg-slate-950/40 backdrop-blur-md border border-slate-800/80 rounded-3xl p-8 md:p-10 shadow-2xl text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Inner gradients backdrops -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl"></div>

                <div class="relative z-10 space-y-3 max-w-xl text-center md:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-xs font-semibold">
                        📱 Aplikasi Web Progresif (PWA)
                    </span>
                    <h2 class="text-2xl font-extrabold outfit text-white">Pasang Aplikasi Smartagro</h2>
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
        </div>
        <!-- Installation Instructions Modal (Fallback) -->
        <div x-show="showAndroidModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showAndroidModal = false" class="bg-white rounded-3xl p-8 max-w-md w-full text-slate-800 relative shadow-2xl border border-slate-100">
                <button @click="showAndroidModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 focus:outline-none text-xl">✕</button>
                
                <div class="text-center space-y-3 mb-6">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto text-3xl">
                        📲
                    </div>
                    <h3 class="text-2xl font-bold outfit text-slate-900">Pasang Aplikasi Smartagro</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pasang aplikasi **Smartagro** di layar perangkat Anda untuk akses lebih cepat, responsif, dan hemat kuota.
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
                <span class="font-bold text-xl outfit text-white">Smart<span class="text-emerald-500">agro</span></span>
            </div>
            <p class="text-slate-400 text-sm">© {{ date('Y') }} Smartagro. All rights reserved.</p>
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

        function qrScanner() {
            return {
                selectedBatch: 'batch-1',
                isScanning: false,
                scanCompleted: false,
                showResult: false,
                searchToken: '',
                isRealCameraActive: false,
                facingMode: 'environment',
                errorMessage: '',
                html5QrCodeInstance: null,
                batches: {
                    'batch-1': {
                        id: 'KTG-0824-GRN',
                        varietas: 'Kentang Granola Super',
                        grade: 'Grade A (Premium)',
                        berat: '50 Kg',
                        petani: 'Pak Wawan - Kelompok Tani Dieng',
                        koperasi: 'Koperasi Dieng Makmur',
                        lokasi: 'Dataran Tinggi Dieng, Wonosobo',
                        ketinggian: '2.090 mdpl',
                        tanggalPanen: '05 Agustus 2026',
                        tanggalKirim: '06 Agustus 2026',
                        tujuan: 'Pasar Induk Kramat Jati, Jakarta',
                        jarak: '430 Km',
                        ruteInfo: 'Jalur Tol Trans Jawa (Cepat & Mulus)',
                        truckNo: 'B 9482 SXA',
                        waktuTempuh: '6 Jam 15 Menit',
                        suhuKargo: '16°C (Optimal)',
                        kategoriColor: 'text-amber-600 bg-amber-50 border-amber-100',
                        gradeColor: 'text-emerald-700 bg-emerald-50 border-emerald-100',
                        mapSvg: `<svg class='w-full h-full' viewBox='0 0 300 120' fill='none' xmlns='http://www.w3.org/2000/svg'>
                            <path d='M10 20 H290 M10 40 H290 M10 60 H290 M10 80 H290 M10 100 H290' stroke='#f1f5f9' stroke-width='1'/>
                            <path d='M50 10 V110 M100 10 V110 M150 10 V110 M200 10 V110 M250 10 V110' stroke='#f1f5f9' stroke-width='1'/>
                            <path d='M60 80 Q 120 100 160 60 T 240 40' stroke='#e2e8f0' stroke-width='4' stroke-linecap='round'/>
                            <path d='M60 80 Q 120 100 160 60 T 240 40' stroke='#6366f1' stroke-width='3' stroke-linecap='round' stroke-dasharray='6 4' class='animate-route'/>
                            <path d='M60 80 Q 120 100 160 60 T 240 40' stroke='#10b981' stroke-width='1.5' stroke-linecap='round'/>
                            <circle cx='60' cy='80' r='6' fill='#f59e0b' stroke='white' stroke-width='2' class='animate-pulse'/>
                            <circle cx='60' cy='80' r='4' fill='#f59e0b'/>
                            <text x='52' y='95' fill='#64748b' class='text-[8px] font-extrabold outfit'>DIENG</text>
                            <circle cx='240' cy='40' r='4' fill='#ef4444' stroke='white' stroke-width='1.5'/>
                            <circle cx='240' cy='40' r='2.5' fill='#ef4444'/>
                            <text x='225' y='32' fill='#64748b' class='text-[8px] font-extrabold outfit'>JAKARTA</text>
                            <text x='15' y='105' fill='#94a3b8' class='text-[7px] font-mono tracking-wider font-bold'>DIENG → JAKARTA</text>
                            <g>
                                <path d='M-6 -2 H1 L3 0 V2 H-6 V-2 Z' fill='#0f172a'/>
                                <rect x='-8' y='-1' width='2.5' height='2.5' fill='#38bdf8'/>
                                <circle cx='-3.5' cy='2.5' r='1' fill='#64748b'/>
                                <circle cx='1.5' cy='2.5' r='1' fill='#64748b'/>
                                <animateMotion dur='7s' repeatCount='indefinite' rotate='auto' path='M60 80 Q 120 100 160 60 T 240 40'/>
                            </g>
                        </svg>`
                    },
                    'batch-2': {
                        id: 'KTG-0825-ATL',
                        varietas: 'Kentang Atlantic Industri',
                        grade: 'Grade B (Standar Olahan)',
                        berat: '45 Kg',
                        petani: 'Pak Sugeng - Tani Pangalengan',
                        koperasi: 'Koperasi Tani Pangalengan',
                        lokasi: 'Pangalengan, Bandung',
                        ketinggian: '1.420 mdpl',
                        tanggalPanen: '06 Agustus 2026',
                        tanggalKirim: '07 Agustus 2026',
                        tujuan: 'Kawasan Industri Cikarang, Bekasi',
                        jarak: '180 Km',
                        ruteInfo: 'Jalur Tol Cipularang (Bebas Hambatan)',
                        truckNo: 'D 8203 YC',
                        waktuTempuh: '3 Jam 45 Menit',
                        suhuKargo: '17°C (Optimal)',
                        kategoriColor: 'text-blue-600 bg-blue-50 border-blue-100',
                        gradeColor: 'text-amber-700 bg-amber-50 border-amber-100',
                        mapSvg: `<svg class='w-full h-full' viewBox='0 0 300 120' fill='none' xmlns='http://www.w3.org/2000/svg'>
                            <path d='M10 20 H290 M10 40 H290 M10 60 H290 M10 80 H290 M10 100 H290' stroke='#f1f5f9' stroke-width='1'/>
                            <path d='M50 10 V110 M100 10 V110 M150 10 V110 M200 10 V110 M250 10 V110' stroke='#f1f5f9' stroke-width='1'/>
                            <path d='M90 90 Q 140 75 160 65 T 210 40' stroke='#e2e8f0' stroke-width='4' stroke-linecap='round'/>
                            <path d='M90 90 Q 140 75 160 65 T 210 40' stroke='#6366f1' stroke-width='3' stroke-linecap='round' stroke-dasharray='6 4' class='animate-route'/>
                            <path d='M90 90 Q 140 75 160 65 T 210 40' stroke='#10b981' stroke-width='1.5' stroke-linecap='round'/>
                            <circle cx='90' cy='90' r='6' fill='#f59e0b' stroke='white' stroke-width='2' class='animate-pulse'/>
                            <circle cx='90' cy='90' r='4' fill='#f59e0b'/>
                            <text x='70' y='105' fill='#64748b' class='text-[8px] font-extrabold outfit'>PANGALENGAN</text>
                            <circle cx='210' cy='40' r='4' fill='#ef4444' stroke='white' stroke-width='1.5'/>
                            <circle cx='210' cy='40' r='2.5' fill='#ef4444'/>
                            <text x='200' y='32' fill='#64748b' class='text-[8px] font-extrabold outfit'>CIKARANG</text>
                            <text x='15' y='105' fill='#94a3b8' class='text-[7px] font-mono tracking-wider font-bold'>PANGALENGAN → CIKARANG</text>
                            <g>
                                <path d='M-6 -2 H1 L3 0 V2 H-6 V-2 Z' fill='#0f172a'/>
                                <rect x='-8' y='-1' width='2.5' height='2.5' fill='#38bdf8'/>
                                <circle cx='-3.5' cy='2.5' r='1' fill='#64748b'/>
                                <circle cx='1.5' cy='2.5' r='1' fill='#64748b'/>
                                <animateMotion dur='7s' repeatCount='indefinite' rotate='auto' path='M90 90 Q 140 75 160 65 T 210 40'/>
                            </g>
                        </svg>`
                    },
                    'batch-3': {
                        id: 'KTG-0826-MRH',
                        varietas: 'Kentang Merah Premium',
                        grade: 'Grade A+ (Organik Super)',
                        berat: '40 Kg',
                        petani: 'Ibu Ratna - Agro Lestari Bromo',
                        koperasi: 'Koperasi Bromo Sejahtera',
                        lokasi: 'Tengger, Probolinggo',
                        ketinggian: '2.320 mdpl',
                        tanggalPanen: '04 Agustus 2026',
                        tanggalKirim: '05 Agustus 2026',
                        tujuan: 'Supermarket Organik, Kelapa Gading',
                        jarak: '810 Km',
                        ruteInfo: 'Jalur Tol Trans Jawa (Ekspres Logistik)',
                        truckNo: 'N 7492 UY',
                        waktuTempuh: '11 Jam 30 Menit',
                        suhuKargo: '14°C (Kargo Pendingin)',
                        kategoriColor: 'text-rose-600 bg-rose-50 border-rose-100',
                        gradeColor: 'text-indigo-700 bg-indigo-50 border-indigo-100',
                        mapSvg: `<svg class='w-full h-full' viewBox='0 0 300 120' fill='none' xmlns='http://www.w3.org/2000/svg'>
                            <path d='M10 20 H290 M10 40 H290 M10 60 H290 M10 80 H290 M10 100 H290' stroke='#f1f5f9' stroke-width='1'/>
                            <path d='M50 10 V110 M100 10 V110 M150 10 V110 M200 10 V110 M250 10 V110' stroke='#f1f5f9' stroke-width='1'/>
                            <path d='M240 90 C 180 80 120 70 60 40' stroke='#e2e8f0' stroke-width='4' stroke-linecap='round'/>
                            <path d='M240 90 C 180 80 120 70 60 40' stroke='#6366f1' stroke-width='3' stroke-linecap='round' stroke-dasharray='6 4' class='animate-route'/>
                            <path d='M240 90 C 180 80 120 70 60 40' stroke='#10b981' stroke-width='1.5' stroke-linecap='round'/>
                            <circle cx='240' cy='90' r='6' fill='#f59e0b' stroke='white' stroke-width='2' class='animate-pulse'/>
                            <circle cx='240' cy='90' r='4' fill='#f59e0b'/>
                            <text x='225' y='105' fill='#64748b' class='text-[8px] font-extrabold outfit'>BROMO</text>
                            <circle cx='60' cy='40' r='4' fill='#ef4444' stroke='white' stroke-width='1.5'/>
                            <circle cx='60' cy='40' r='2.5' fill='#ef4444'/>
                            <text x='42' y='32' fill='#64748b' class='text-[8px] font-extrabold outfit'>JAKARTA</text>
                            <text x='15' y='105' fill='#94a3b8' class='text-[7px] font-mono tracking-wider font-bold'>BROMO → JAKARTA</text>
                            <g>
                                <path d='M-6 -2 H1 L3 0 V2 H-6 V-2 Z' fill='#0f172a'/>
                                <rect x='-8' y='-1' width='2.5' height='2.5' fill='#38bdf8'/>
                                <circle cx='-3.5' cy='2.5' r='1' fill='#64748b'/>
                                <circle cx='1.5' cy='2.5' r='1' fill='#64748b'/>
                                <animateMotion dur='7s' repeatCount='indefinite' rotate='auto' path='M240 90 C 180 80 120 70 60 40'/>
                            </g>
                        </svg>`
                    }
                },
                initScanner() {
                    if (!this.html5QrCodeInstance) {
                        this.html5QrCodeInstance = new Html5Qrcode("interactive-camera");
                    }
                },
                async startRealCamera() {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.errorMessage = 'Akses kamera ditolak oleh browser Anda. Pastikan Anda menggunakan koneksi aman (HTTPS atau localhost) dan memberikan izin akses kamera.';
                        return;
                    }
                    this.initScanner();
                    this.isRealCameraActive = true;
                    this.errorMessage = '';
                    
                    const config = { fps: 15, qrbox: { width: 180, height: 180 } };
                    try {
                        await this.html5QrCodeInstance.start(
                            { facingMode: this.facingMode },
                            config,
                            (decodedText) => {
                                this.handleScanSuccess(decodedText);
                            },
                            (err) => {
                                // Silent error
                            }
                        );
                    } catch (err) {
                        this.errorMessage = 'Akses kamera gagal: ' + err.message + '. Pastikan izin kamera telah diberikan di pengaturan browser Anda.';
                        this.isRealCameraActive = false;
                    }
                },
                async stopRealCamera() {
                    if (this.html5QrCodeInstance && this.isRealCameraActive) {
                        try {
                            await this.html5QrCodeInstance.stop();
                        } catch (e) {
                            console.error(e);
                        }
                        this.isRealCameraActive = false;
                    }
                },
                async flipCamera() {
                    if (!this.isRealCameraActive) return;
                    this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
                    await this.stopRealCamera();
                    await this.startRealCamera();
                },
                async handleImageUpload(event) {
                    this.initScanner();
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    this.errorMessage = '';
                    try {
                        const decodedText = await this.html5QrCodeInstance.scanFile(file, true);
                        this.handleScanSuccess(decodedText);
                    } catch (err) {
                        this.errorMessage = 'Tidak dapat membaca QR. Pastikan gambar QR code jelas.';
                    }
                },
                handleScanSuccess(decodedText) {
                    this.playBeep();
                    this.stopRealCamera();
                    
                    let token = decodedText;
                    if (decodedText.includes('/lacak/')) {
                        const parts = decodedText.split('/lacak/');
                        token = parts[parts.length - 1];
                    }
                    
                    if (token === 'KTG-0824-GRN') {
                        this.selectedBatch = 'batch-1';
                        this.scanCompleted = true;
                        this.showResult = true;
                        return;
                    } else if (token === 'KTG-0825-ATL') {
                        this.selectedBatch = 'batch-2';
                        this.scanCompleted = true;
                        this.showResult = true;
                        return;
                    } else if (token === 'KTG-0826-MRH') {
                        this.selectedBatch = 'batch-3';
                        this.scanCompleted = true;
                        this.showResult = true;
                        return;
                    }
                    
                    fetch('/api/lacak/' + token)
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {
                                this.batches['scanned'] = response.data;
                                this.selectedBatch = 'scanned';
                                this.scanCompleted = true;
                                this.showResult = true;
                            } else {
                                this.errorMessage = 'QR valid, tetapi data transaksi tidak ditemukan.';
                            }
                        })
                        .catch(err => {
                            this.errorMessage = 'Terjadi kesalahan saat memuat data pelacakan.';
                        });
                },
                playBeep() {
                    try {
                        const context = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = context.createOscillator();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, context.currentTime);
                        osc.connect(context.destination);
                        osc.start();
                        osc.stop(context.currentTime + 0.15);
                    } catch (e) {
                        console.error(e);
                    }
                }
            };
        }
    </script>
</body>
</html>
