<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Smart Agroindustri</title>
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
<body class="antialiased min-h-screen flex items-center justify-center p-4 py-10">

    <div class="w-full max-w-lg">
        
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex flex-col items-center gap-3 mb-2">
                <div class="w-24 h-24 flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}?v={{ time() }}" alt="Logo" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <span class="font-bold text-3xl outfit tracking-tight text-slate-900">Agro<span class="text-blue-600">industri</span></span>
            </a>
            <p class="text-slate-500 text-sm">Bergabung dengan ekosistem digital pertanian</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium mb-6 flex flex-col gap-1">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Terdapat kesalahan:
                </div>
                <ul class="list-disc list-inside ml-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 relative">
            
            <h2 class="text-2xl font-bold outfit text-slate-900 mb-2">Buat Akun Baru</h2>
            <p class="text-sm text-slate-500 mb-6">Lengkapi form di bawah untuk mendaftar.</p>

            <form method="POST" action="{{ route('register.post') }}" class="space-y-5" x-data="{ role: '{{ old('role', 'petani') }}' }">
                @csrf
                
                <!-- Role Selection using Cards -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Daftar Sebagai</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Petani Card -->
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="petani" class="peer sr-only" x-model="role">
                            <div class="p-4 rounded-xl border-2 border-slate-100 hover:bg-slate-50 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-center">
                                <div class="w-10 h-10 mx-auto bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mb-2 peer-checked:bg-emerald-600 peer-checked:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path></svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm outfit">Petani</h3>
                            </div>
                            <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-emerald-600 transition-opacity">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>

                        <!-- Koperasi Card -->
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="koperasi" class="peer sr-only" x-model="role">
                            <div class="p-4 rounded-xl border-2 border-slate-100 hover:bg-slate-50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center">
                                <div class="w-10 h-10 mx-auto bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-2 peer-checked:bg-blue-600 peer-checked:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm outfit">Koperasi</h3>
                            </div>
                            <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-blue-600 transition-opacity">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-sm text-slate-800">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: budi@example.com" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-sm text-slate-800">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-sm text-slate-800">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Ulangi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi password" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-sm text-slate-800">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 hover:-translate-y-0.5 mt-4">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Masuk di sini</a></p>
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
