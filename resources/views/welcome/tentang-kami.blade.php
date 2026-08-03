<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami - Smart Agroindustri</title>
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
    </style>
</head>
<body class="antialiased bg-white text-slate-800">

    <!-- Navbar -->
    @include('welcome.partials.navbar')

    <!-- Header Hero Banner -->
    <div class="relative pt-32 pb-16 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-950 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-500 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center py-12 md:py-20">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-4 uppercase tracking-widest outfit">
                Profil Perusahaan
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 outfit">Tentang Kami</h1>
            <p class="text-slate-300 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                Mengenal lebih dekat visi, misi, dan nilai-nilai Smart Agroindustri dalam merajut rantai nilai pertanian modern.
            </p>
        </div>
    </div>

    <!-- Main Content Detail -->
    <div class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-12 gap-12 items-start">
                
                <!-- Left: Quick Facts/Details card -->
                <div class="md:col-span-5 space-y-6 sticky top-28">
                    <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 shadow-md">
                        <h3 class="text-xl font-bold outfit text-slate-900 mb-4 border-b border-slate-200/60 pb-3">Agroindustri Cerdas</h3>
                        <ul class="space-y-4 text-sm">
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 font-bold mt-0.5">✔</span>
                                <div>
                                    <strong class="block text-slate-800">Rantai Nilai Terbuka</strong>
                                    <span class="text-slate-500">Keterbukaan harga dan data logistik hasil bumi.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-emerald-600 font-bold mt-0.5">✔</span>
                                <div>
                                    <strong class="block text-slate-800">Dukungan Petani Lokal</strong>
                                    <span class="text-slate-500">Membantu pendistribusian benih unggul hingga pupuk.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-purple-600 font-bold mt-0.5">✔</span>
                                <div>
                                    <strong class="block text-slate-800">Teknologi Finansial</strong>
                                    <span class="text-slate-500">Gerbang pembayaran cepat (Midtrans QRIS & VA).</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="p-8 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-xl">
                        <h4 class="text-lg font-bold outfit mb-2">Butuh Bantuan Teknis?</h4>
                        <p class="text-xs text-blue-100/90 leading-relaxed mb-6">Hubungi administrator kami jika Anda mengalami kesulitan dalam pendaftaran mitra.</p>
                        <a href="mailto:support@agroindustri.id" class="inline-flex items-center justify-center w-full py-3 bg-white text-blue-700 font-bold rounded-xl text-xs hover:bg-slate-50 transition">
                            📧 Hubungi Support
                        </a>
                    </div>
                </div>

                <!-- Right: Detailed Vision / Mission -->
                <div class="md:col-span-7 space-y-12">
                    <section class="space-y-4">
                        <h2 class="text-2xl font-bold text-slate-900 outfit flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                            Siapa Kami?
                        </h2>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Smart Agroindustri didirikan atas dasar kesadaran akan pentingnya efisiensi logistik pangan nasional. Kami mempertemukan para penyedia bibit, petani pengelola lahan, koperasi unit desa sebagai pengumpul, serta distributor pasar dalam satu rantai data digital yang koheren.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Melalui integrasi data berbasis teknologi, kami berupaya memotong jalur distribusi pertanian yang tidak efisien agar menghasilkan harga yang adil bagi petani dan kualitas pangan terbaik bagi masyarakat.
                        </p>
                    </section>

                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-slate-900 outfit flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-emerald-600 rounded-full"></span>
                            Visi & Misi Kami
                        </h2>
                        
                        <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-2">
                            <h4 class="font-bold text-slate-800 text-sm">Visi Utama</h4>
                            <p class="text-slate-500 text-xs italic">
                                "Menjadi katalisator rantai nilai pertanian digital terdepan di Indonesia demi mewujudkan kedaulatan pangan nasional yang berkeadilan sosial."
                            </p>
                        </div>

                        <div class="space-y-4">
                            <h4 class="font-bold text-slate-800 text-sm">Misi Kami</h4>
                            <ol class="list-decimal pl-5 text-slate-600 text-xs space-y-2.5">
                                <li>Membangun platform supply chain digital pertanian yang mudah diakses dan transparan bagi semua pelaku pasar.</li>
                                <li>Memberdayakan petani kecil melalui integrasi data pergudangan, ketersediaan bibit unggul, serta skema pembayaran instan.</li>
                                <li>Mendorong pertumbuhan ekonomi pedesaan dengan mendigitalisasi koperasi primer selaku pengumpul lokal hasil pertanian.</li>
                            </ol>
                        </div>
                    </section>
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
    </script>
</body>
</html>
