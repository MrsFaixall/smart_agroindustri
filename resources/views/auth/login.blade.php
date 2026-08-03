<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Smart Agroindustri</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- PWA Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#f8fafc">
    <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        h1, h2, h3, h4, h5, h6, .outfit { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md" x-data="{ 
            step: 1, 
            selectedRole: '', 
            roleName: '',
            selectRole(role, name) {
                this.selectedRole = role;
                this.roleName = name;
                this.step = 2;
            }
        }">
        
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex flex-col items-center gap-3 mb-2">
                <div class="w-24 h-24 flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}?v={{ time() }}" alt="Logo" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <span class="font-bold text-3xl outfit tracking-tight text-slate-900">Agro<span class="text-blue-600">industri</span></span>
            </a>
            <p class="text-slate-500 text-sm">Platform Logistik & Rantai Pasok Terpadu</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
            
            <!-- Step 1: Pilih Role -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8">
                <h2 class="text-2xl font-bold outfit text-slate-900 mb-2">Masuk Sebagai</h2>
                <p class="text-sm text-slate-500 mb-6">Pilih jenis akun Anda untuk melanjutkan login.</p>
                
                <div class="space-y-3">
                    <!-- Role: Petani -->
                    <button @click="selectRole('petani', 'Petani')" class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-100 hover:border-emerald-500 hover:bg-emerald-50 transition-all text-left group">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 outfit">Petani</h3>
                            <p class="text-xs text-slate-500">Kelola panen, stok & harga</p>
                        </div>
                    </button>

                    <!-- Role: Koperasi -->
                    <button @click="selectRole('koperasi', 'Koperasi')" class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-100 hover:border-blue-500 hover:bg-blue-50 transition-all text-left group">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 outfit">Koperasi</h3>
                            <p class="text-xs text-slate-500">Pembelian & Manajemen Gudang</p>
                        </div>
                    </button>

                    <!-- Role: Admin -->
                    <button @click="selectRole('admin', 'Administrator')" class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-100 hover:border-slate-800 hover:bg-slate-100 transition-all text-left group">
                        <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-slate-800 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 outfit">Administrator</h3>
                            <p class="text-xs text-slate-500">Akses penuh sistem</p>
                        </div>
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Daftar sekarang</a></p>
                </div>
            </div>

            <!-- Step 2: Form Login -->
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300 delay-200" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8">
                
                <button @click="step = 1" class="flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-slate-600 mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </button>

                <h2 class="text-2xl font-bold outfit text-slate-900 mb-2">Selamat Datang</h2>
                <p class="text-sm text-slate-500 mb-8">Silakan masuk sebagai <span class="font-bold text-slate-800" x-text="roleName"></span>.</p>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf
                    <!-- Hidden input to pass role just in case -->
                    <input type="hidden" name="role" x-model="selectedRole">
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email" id="email" name="email" required placeholder="Masukkan email Anda" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-sm text-slate-800">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                            <a href="#" class="text-xs font-medium text-blue-600 hover:underline">Lupa Password?</a>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password Anda" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-sm text-slate-800">
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 hover:-translate-y-0.5 mt-2">
                        Masuk ke Dasbor
                    </button>
                </form>

            </div>

        </div>
    </div>
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
    </script>
</body>
</html>