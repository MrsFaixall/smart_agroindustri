<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agroindustri</title>
    <!-- Logo Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='25' fill='%23001842'/%3E%3Cpath fill='%23ffffff' d='M55 42V12L18 58h30v30l37-46H55z'/%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
    </style>
    @stack('styles')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white h-screen border-r border-slate-100 flex flex-col sticky top-0 shadow-sm">
        <!-- Header Sidebar -->
        <div class="px-6 py-8 flex items-center gap-3">
            <div class="bg-[#001842] p-2.5 rounded-xl shadow-lg shadow-blue-900/20">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-slate-900 leading-tight">Smart<br>Agroindustri</h1>
                <span
                    class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded uppercase font-bold tracking-wider">
                    {{ auth()->user()->role ?? 'Admin, Petani, Koperasi, Superadmin' }}
                </span>
            </div>
        </div>

        <!-- Navigasi dengan Pengelompokan -->
        <nav class="flex-1 px-4 space-y-8 overflow-y-auto pb-8">

            <!-- Group Utama -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Utama</p>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Dashboard</span>
                </a>
            </div>

            <!-- Group Manajemen admin -->
            <div x-data="{ open: false }">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Manajemen Logistik
           </p>
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 transition">
                    <span class="font-semibold text-sm">Master Data</span>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('admin.bbm.index') }}"
                        class="block px-4 py-2 text-sm text-slate-500 hover:text-[#001842] hover:font-bold">
                        - BBM
                    </a>
                    <a href="{{ route('admin.jenis_kentang.index') }}"
                        class="block px-4 py-2 text-sm text-slate-500 hover:text-[#001842] hover:font-bold">
                        - Jenis Kentang
                    </a>
                </div>
            </div>
            <!-- Group Manajemen PETANI -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Manajemen petani
                </p>
                <a href="{{ route('gudang.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('gudang.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Gudang</span>
            </a>
                <a href="{{ url('/atur-harga') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('atur-harga.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Atur Harga</span>
                </a>
                <a href="{{ route('panen.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('panen.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Panen</span>
                </a>
                <a href="{{ route('stok.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('stok.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Stok</span>
                </a>
                <a href="{{ route('metode-pembayaran.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('metode-pembayaran.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Metode Pembayaran</span>
                </a>
            </div>
             <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Manajemen Akun
                </p>
                <a href="{{ route('pengguna.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pengguna.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Pengguna</span>
            </a>
            </div>

            <!-- Group Transaksi & Pengaturan -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Transaksi & Lainnya
                </p>
                
                <a href="{{ route('pembelian.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pembelian.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Pembelian</span>
                </a>
                <a href="{{ route('pembayaran.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pembayaran.index') || request()->routeIs('pembayaran.create') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Pembayaran</span>
                </a>
                <a href="{{ route('daftar-transaksi.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('daftar-transaksi.*') || request()->routeIs('pembayaran.invoice') || request()->routeIs('pembayaran.struk') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Daftar Transaksi</span>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('laporan.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Laporan</span>
                </a>
                <a href="{{ route('pengaturan.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pengaturan.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Pengaturan</span>
                </a>
            </div>
        </nav>

        <!-- Footer Sidebar -->
        <div class="px-6 py-6 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 text-slate-500 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="font-semibold text-sm">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <header class="bg-white border-b px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div class="relative w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" placeholder="Cari data di aplikasi..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#001842] focus:border-[#001842] transition-all">
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 pl-6 border-l">
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-800">
                            {{ auth()->user()->name ?? 'Pengguna' }}
                        </p>
                        <span
                            class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded uppercase font-bold tracking-wider">
                            {{ auth()->user()->role ?? 'Admin, Petani, Koperasi, Superadmin' }}
                        </span>
                    </div>
                    <img src="https://i.pravatar.cc/40" class="w-10 h-10 rounded-full" alt="Admin">
                </div>
            </div>
        </header>
        <main class="p-8">@yield('content')</main>
    </div>
    @stack('scripts')
</body>

</html>