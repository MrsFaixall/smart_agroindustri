<nav class="fixed w-full top-0 left-0 z-50 glass transition-all duration-300" x-data="{ scrolled: false, open: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Left: Logo & Brand Name -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 flex-shrink-0">
                <div class="w-12 h-12 flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}?v={{ time() }}" alt="Logo" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <span class="font-bold text-2xl outfit tracking-tight text-slate-900">Agro<span class="text-blue-600">industri</span></span>
            </a>

            <!-- Center: Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center justify-center space-x-8 flex-1">
                <a href="{{ url('/') }}" class="text-sm font-semibold {{ request()->is('/') ? 'text-blue-600 font-bold' : 'text-slate-600 hover:text-blue-600' }} transition">Home</a>
                <a href="{{ route('welcome.layanan') }}" class="text-sm font-semibold {{ request()->routeIs('welcome.layanan') ? 'text-blue-600 font-bold' : 'text-slate-600 hover:text-blue-600' }} transition">Layanan</a>
                <a href="{{ route('welcome.tentang-kami') }}" class="text-sm font-semibold {{ request()->routeIs('welcome.tentang-kami') ? 'text-blue-600 font-bold' : 'text-slate-600 hover:text-blue-600' }} transition">Tentang Kami</a>
                <a href="{{ url('/#fitur') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition">Insights</a>
                <a href="{{ route('welcome.kontak') }}" class="text-sm font-semibold {{ request()->routeIs('welcome.kontak') ? 'text-blue-600 font-bold' : 'text-slate-600 hover:text-blue-600' }} transition">Kontak</a>
            </div>

            <!-- Right: Auth Buttons (Desktop) -->
            <div class="hidden md:flex items-center space-x-4 flex-shrink-0">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 transition">Masuk</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-blue-600 rounded-full hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-0.5">
                    Daftar Sekarang
                </a>
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2 rounded-xl hover:bg-slate-100 transition-colors" aria-label="Toggle menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!open">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="open" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Collapsible Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md" 
         style="display: none;">
        <div class="px-4 pt-2 pb-6 space-y-4 shadow-xl shadow-slate-100/50">
            <a href="{{ url('/') }}" @click="open = false" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->is('/') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-slate-700 hover:bg-slate-50 hover:text-blue-600' }} transition">Home</a>
            <a href="{{ route('welcome.layanan') }}" @click="open = false" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('welcome.layanan') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-slate-700 hover:bg-slate-50 hover:text-blue-600' }} transition">Layanan</a>
            <a href="{{ route('welcome.tentang-kami') }}" @click="open = false" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('welcome.tentang-kami') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-slate-700 hover:bg-slate-50 hover:text-blue-600' }} transition">Tentang Kami</a>
            <a href="{{ url('/#fitur') }}" @click="open = false" class="block px-3 py-2.5 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">Insights</a>
            <a href="{{ route('welcome.kontak') }}" @click="open = false" class="block px-3 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('welcome.kontak') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-slate-700 hover:bg-slate-50 hover:text-blue-600' }} transition">Kontak</a>
            <hr class="border-slate-100 my-2">
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}" class="flex justify-center items-center px-4 py-3 rounded-full text-base font-bold text-slate-700 hover:bg-slate-50 transition border border-slate-200">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="flex justify-center items-center px-4 py-3 rounded-full text-base font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/10 transition">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</nav>
