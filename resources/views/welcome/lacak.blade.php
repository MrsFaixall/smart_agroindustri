<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smartagro - Pelacakan Batch Kentang #{{ $transaksi->tracking_token }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .outfit { font-family: 'Outfit', sans-serif; }
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
<body class="antialiased bg-slate-50 text-slate-800" x-data="{ 
    loading: true, 
    init() { 
        setTimeout(() => this.loading = false, 1500); 
    } 
}">

    <!-- Page Loader / Scanning Animation -->
    <div x-show="loading" class="fixed inset-0 z-50 bg-slate-950 flex flex-col items-center justify-center p-4">
        <div class="relative w-64 h-64 border border-white/10 rounded-2xl flex items-center justify-center overflow-hidden">
            <div class="absolute left-0 right-0 h-1 bg-emerald-500 shadow-[0_0_15px_#10b981] scanner-line z-20"></div>
            <!-- Target corners -->
            <div class="absolute inset-6 border border-emerald-500/20 rounded-lg flex items-center justify-center">
                <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-emerald-500"></div>
                <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-emerald-500"></div>
                <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-emerald-500"></div>
                <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-emerald-500"></div>
                
                <svg class="w-24 h-24 text-emerald-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm14-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm13-2h3v2h-3v-2zm3 3h3v3h-3v-3zm-3 3h3v-3h-3v3zm-3-3h3v3h-3v-3zm3-3h-3v3h3v-3zm-6-2v3h-3v2h5v-5h-2zm4 4v3h2v-3h-2zm-2 2h2v2h-2v-2z"/>
                </svg>
            </div>
        </div>
        <h2 class="text-white text-lg font-bold outfit mt-6 tracking-wide animate-pulse">MEMINDAI SMART QR...</h2>
        <p class="text-slate-400 text-xs mt-2 font-mono">Token: {{ substr($transaksi->tracking_token, 0, 8) }}...</p>
    </div>

    <!-- Main Content (Revealed after scan loading) -->
    <div x-show="!loading" x-transition.opacity.duration.500 class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col justify-between max-w-xl mx-auto" style="display: none;">
        
        <!-- Header Brand -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-emerald-200">
                    S
                </div>
                <div>
                    <span class="font-bold text-lg outfit tracking-tight text-slate-900">Smart<span class="text-emerald-600">agro</span></span>
                    <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-widest">Digital supply chain</span>
                </div>
            </div>
            <a href="{{ url('/') }}" class="text-xs text-slate-500 hover:text-emerald-600 transition font-bold">Ke Halaman Utama &rarr;</a>
        </div>

        <!-- Result Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden flex flex-col">
            
            <!-- Card Banner -->
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 p-6 text-white relative">
                <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md text-white text-[9px] font-bold px-3 py-1 rounded-full border border-white/20 flex items-center gap-1.5 uppercase tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span> Terverifikasi
                </div>
                <span class="text-[9px] font-mono tracking-widest text-emerald-100 uppercase block">SKU / Batch Transaksi</span>
                <h1 class="text-2xl font-extrabold outfit mt-1">{{ $transaksi->jenisKentang->nama_jenis ?? 'Kentang Granola' }}</h1>
                <p class="text-xs text-emerald-100 mt-1">Asal: {{ $transaksi->koperasi->name ?? 'Koperasi Dieng' }}</p>
            </div>

            <!-- Specifications -->
            <div class="p-6 space-y-6 bg-white">
                
                <!-- Grade and Weight metrics -->
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <span class="block text-[9px] font-bold text-slate-400 uppercase">Grade Mutu</span>
                        <span class="text-sm font-extrabold text-slate-800">{{ $transaksi->grade ?? 'Grade A' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <span class="block text-[9px] font-bold text-slate-400 uppercase">Volume</span>
                        <span class="text-sm font-extrabold text-slate-800">{{ number_format($transaksi->jumlah_kg, 0, ',', '.') }} Kg</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <span class="block text-[9px] font-bold text-slate-400 uppercase">Ketinggian</span>
                        <span class="text-sm font-extrabold text-slate-800">2.090 mdpl</span>
                    </div>
                </div>

                <!-- Logistics & Origin details -->
                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-3">Informasi Asal & Kualitas</h3>
                    <div class="grid grid-cols-1 gap-3 text-sm text-slate-700">
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Pengepul Koperasi</span>
                            <span class="font-bold text-slate-800">{{ $transaksi->koperasi->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Pembeli (B2B)</span>
                            <span class="font-bold text-slate-800">{{ $transaksi->pembeli->name ?? 'PT Camp' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Tanggal Kirim</span>
                            <span class="font-bold text-slate-800">{{ date('d F Y', strtotime($transaksi->tanggal_transaksi)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Status Kelayakan</span>
                            <span class="font-bold text-emerald-600 flex items-center gap-1">✓ Lolos Uji Konsumsi</span>
                        </div>
                    </div>
                </div>

                <!-- Map Tracking / Smart Routing -->
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xs font-bold text-slate-400 uppercase">Rute Logistik Cerdas</h3>
                        <span class="text-[9px] text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-bold">ARMADA: B 9482 SXA</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-xs text-slate-700 bg-slate-50/50 rounded-2xl p-4 border border-slate-100/50">
                        <div>
                            <span class="text-slate-400 block">Jalur Tempuh</span>
                            <span class="font-bold text-slate-800">Trans Jawa (Tol)</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Waktu Estimasi</span>
                            <span class="font-bold text-indigo-600">{{ $transaksi->estimasi_waktu ?? '6 Jam 15 Menit' }}</span>
                        </div>
                    </div>

                    <!-- SVG Route Path render -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl h-36 overflow-hidden relative">
                        @php
                            $origin = $transaksi->koperasi->name ?? 'Koperasi Tani';
                            $dest = $transaksi->pembeli->name ?? 'PT Camp';
                            $isPtCamp = stripos($dest, 'camp') !== false;
                        @endphp
                        @if($isPtCamp)
                            <svg class="w-full h-full" viewBox="0 0 300 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 20 H290 M10 40 H290 M10 60 H290 M10 80 H290 M10 100 H290" stroke="#f1f5f9" stroke-width="1"/>
                                <path d="M50 10 V110 M100 10 V110 M150 10 V110 M200 10 V110 M250 10 V110" stroke="#f1f5f9" stroke-width="1"/>
                                <circle cx="60" cy="80" r="6" fill="#f59e0b" stroke="white" stroke-width="2" class="animate-pulse"/>
                                <circle cx="60" cy="80" r="4" fill="#f59e0b"/>
                                <text x="52" y="95" fill="#64748b" class="text-[8px] font-extrabold outfit">{{ $origin }}</text>
                                <circle cx="240" cy="40" r="4" fill="#10b981"/>
                                <text x="225" y="32" fill="#64748b" class="text-[8px] font-extrabold outfit">{{ $dest }}</text>
                                <path d="M60 80 Q 120 100 160 60 T 240 40" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-dasharray="6 4" class="animate-route"/>
                                <path d="M60 80 Q 120 100 160 60 T 240 40" stroke="#10b981" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        @else
                            <svg class="w-full h-full" viewBox="0 0 300 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 20 H290 M10 40 H290 M10 60 H290 M10 80 H290 M10 100 H290" stroke="#f1f5f9" stroke-width="1"/>
                                <path d="M50 10 V110 M100 10 V110 M150 10 V110 M200 10 V110 M250 10 V110" stroke="#f1f5f9" stroke-width="1"/>
                                <circle cx="90" cy="90" r="6" fill="#f59e0b" stroke="white" stroke-width="2" class="animate-pulse"/>
                                <circle cx="90" cy="90" r="4" fill="#f59e0b"/>
                                <text x="70" y="105" fill="#64748b" class="text-[8px] font-extrabold outfit">{{ $origin }}</text>
                                <circle cx="210" cy="40" r="4" fill="#10b981"/>
                                <text x="200" y="32" fill="#64748b" class="text-[8px] font-extrabold outfit">{{ $dest }}</text>
                                <path d="M90 90 Q 140 75 160 65 T 210 40" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-dasharray="6 4" class="animate-route"/>
                                <path d="M90 90 Q 140 75 160 65 T 210 40" stroke="#10b981" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer brand -->
        <div class="mt-8 text-center text-slate-400 text-xs space-y-1">
            <p>© {{ date('Y') }} Smartagro. Hak Cipta Dilindungi.</p>
            <p class="text-[10px] text-slate-300">Rantai pasok terverifikasi QR Code batching platform.</p>
        </div>
    </div>
</body>
</html>
