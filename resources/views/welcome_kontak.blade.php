<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hubungi Kami - Smart Agroindustri</title>
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
    <div class="relative pt-32 pb-16 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-950 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-500 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center py-12 md:py-20">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-4 uppercase tracking-widest outfit">
                Dukungan Pengguna
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 outfit">Hubungi Kami</h1>
            <p class="text-slate-300 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                Kami siap mendengarkan saran, masukan, maupun pertanyaan Anda terkait ekosistem digital Agroindustri.
            </p>
        </div>
    </div>

    <!-- Contact Form & Info Grid -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-16">
                
                <!-- Left: Contact Details -->
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900 outfit mb-4">Dukungan Responsif</h2>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Silakan hubungi kantor operasional kami atau kirim pesan langsung melalui formulir di samping. Tim kami akan segera merespons dalam waktu 24 jam kerja.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                📍
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Alamat Kantor</h4>
                                <p class="text-slate-500 text-xs leading-relaxed">Jl. Raya Agroindustri No. 45, Kawasan Sentra Pertanian Kentang, Bandung, Jawa Barat</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                ✉
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Surat Elektronik</h4>
                                <p class="text-slate-500 text-xs leading-relaxed">support@agroindustri.id</p>
                                <p class="text-slate-500 text-xs leading-relaxed">info@agroindustri.id</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                📞
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Telepon / WhatsApp</h4>
                                <p class="text-slate-500 text-xs leading-relaxed">+62 812-3456-7890</p>
                                <p class="text-slate-500 text-xs leading-relaxed">+62 22-987-654</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="lg:col-span-7">
                    <form action="#" method="POST" class="p-8 rounded-3xl bg-slate-50 border border-slate-100 shadow-xl space-y-6" onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah berhasil dikirim ke administrator.'); this.reset();">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Nama Lengkap</label>
                                <input type="text" id="name" name="name" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs text-slate-800 focus:border-emerald-500 transition-all outline-none bg-white font-medium" placeholder="Masukkan nama Anda" required>
                            </div>
                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Alamat Email</label>
                                <input type="email" id="email" name="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs text-slate-800 focus:border-emerald-500 transition-all outline-none bg-white font-medium" placeholder="nama@email.com" required>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="space-y-1.5">
                            <label for="subject" class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Perihal</label>
                            <input type="text" id="subject" name="subject" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs text-slate-800 focus:border-emerald-500 transition-all outline-none bg-white font-medium" placeholder="Topik pesan Anda" required>
                        </div>

                        <!-- Message -->
                        <div class="space-y-1.5">
                            <label for="message" class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Isi Pesan</label>
                            <textarea id="message" name="message" rows="5" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs text-slate-800 focus:border-emerald-500 transition-all outline-none bg-white font-medium resize-none" placeholder="Tuliskan detail pertanyaan atau masukan Anda..." required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md transition-all uppercase tracking-widest">
                            Kirim Pesan Sekarang
                        </button>
                    </form>
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