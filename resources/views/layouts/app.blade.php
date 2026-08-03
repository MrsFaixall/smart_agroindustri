<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agroindustri</title>
    <!-- Logo Favicon & PWA Tags -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#001842">
    <link rel="apple-touch-icon" href="{{ asset('icon.svg') }}">
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

<body class="bg-slate-50 flex min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-white h-screen border-r border-slate-100 flex flex-col fixed lg:sticky top-0 shadow-sm z-50 transition-transform duration-300 lg:translate-x-0">
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
                    {{ ucfirst(auth()->user()->role ?? session('role', 'Guest')) }}
                </span>
            </div>
        </div>

        @php
            $currentRole = auth()->user()->role ?? session('role');
            $isAdmin = in_array($currentRole, ['admin', 'super admin', 'superadmin']);
            $isKoperasi = $currentRole === 'koperasi';
            $isPetani = $currentRole === 'petani';
            $isMitra = $currentRole === 'mitra';
        @endphp

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

            <!-- Group Manajemen Logistik & Master -->
            @if($isAdmin || $isKoperasi)
            @php
                $isMasterActive = request()->routeIs('admin.bbm.*') || request()->routeIs('admin.jenis_kentang.*') || request()->routeIs('admin.kategori_kentang.*');
            @endphp
            <div x-data="{ open: {{ $isMasterActive ? 'true' : 'false' }} }">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Manajemen Logistik</p>
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isMasterActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition">
                    <span class="font-semibold text-sm">Master Data</span>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('admin.bbm.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('admin.bbm.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - BBM
                    </a>
                    <a href="{{ route('admin.jenis_kentang.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('admin.jenis_kentang.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Jenis Kentang
                    </a>
                    <a href="{{ route('admin.kategori_kentang.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('admin.kategori_kentang.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Kategori Kentang
                    </a>
                </div>
            </div>
            @endif

            <!-- Group 1: MITRA (PT. CHAMP) -->
            @if($isAdmin || $isKoperasi || $isMitra)
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">1. Mitra (PT. Champ)</p>
                <a href="{{ route('pengadaan-benih.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pengadaan-benih.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span class="font-semibold text-sm">Pengadaan Benih</span>
                </a>
                <a href="{{ route('mitra-gudang.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('mitra-gudang.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span class="font-semibold text-sm">Gudang Mitra</span>
                </a>
                <a href="{{ route('pembayaran.index', ['view' => 'mitra']) }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->get('view') === 'mitra' ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    <span class="font-semibold text-sm">Pembayaran Mitra</span>
                </a>
            </div>
            @endif
 
            <!-- Group 2: MANAJEMEN KOPERASI -->
            @if($isAdmin || $isKoperasi)
            @php
                $isKoperasiBenihActive = request()->routeIs('pengajuan-benih.koperasi') || 
                                         request()->routeIs('distribusi-benih.*');
                $isKoperasiPanenActive = request()->routeIs('pembelian.*') || 
                                         request()->routeIs('penjualan-buah.*');
                $isKoperasiRiwayatActive = request()->routeIs('koperasi.layanan.*');
                $isKoperasiLaporanActive = request()->routeIs('koperasi.laporan.*');
                $isKoperasiPembayaranActive = request()->routeIs('pembayaran.index') && !request()->has('view') || request()->routeIs('koperasi.pembayaran.penjualan') || request()->routeIs('koperasi.pembayaran.distribusi');
            @endphp
            <div x-data="{ openBenih: {{ $isKoperasiBenihActive ? 'true' : 'false' }}, openPanen: {{ $isKoperasiPanenActive ? 'true' : 'false' }}, openRiwayat: {{ $isKoperasiRiwayatActive ? 'true' : 'false' }}, openLaporan: {{ $isKoperasiLaporanActive ? 'true' : 'false' }}, openPembayaranKoperasi: {{ $isKoperasiPembayaranActive ? 'true' : 'false' }} }">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">2. Manajemen Koperasi</p>
                
                <!-- Collapsible Kelola Benih -->
                <button type="button" @click="openBenih = !openBenih"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isKoperasiBenihActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        <span class="font-semibold text-sm">Kelola Benih Koperasi</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openBenih ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openBenih" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('pengajuan-benih.koperasi') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('pengajuan-benih.koperasi') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pengajuan Benih
                    </a>
                    <a href="{{ route('distribusi-benih.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('distribusi-benih.index') || (request()->routeIs('distribusi-benih.*') && !request()->routeIs('distribusi-benih.index')) ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Distribusi Benih
                    </a>
                </div>

                <!-- Collapsible Riwayat Layanan Koperasi -->
                <button type="button" @click="openRiwayat = !openRiwayat"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isKoperasiRiwayatActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-2">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-semibold text-sm">Riwayat Layanan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openRiwayat ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openRiwayat" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('koperasi.layanan.riwayat-pengajuan-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.layanan.riwayat-pengajuan-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Pengajuan Benih
                    </a>
                    <a href="{{ route('koperasi.layanan.riwayat-distribusi-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.layanan.riwayat-distribusi-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Distribusi Benih
                    </a>
                    <a href="{{ route('koperasi.layanan.riwayat-penawaran-panen') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.layanan.riwayat-penawaran-panen') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Penawaran Panen
                    </a>
                    <a href="{{ route('koperasi.layanan.riwayat-pembelian') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.layanan.riwayat-pembelian') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Pembelian
                    </a>
                    <a href="{{ route('koperasi.layanan.riwayat-pembayaran') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.layanan.riwayat-pembayaran') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Pembayaran
                    </a>
                </div>

                <!-- Collapsible Jual Beli Panen -->
                <button type="button" @click="openPanen = !openPanen"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isKoperasiPanenActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-2">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span class="font-semibold text-sm">Jual Beli Panen</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openPanen ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openPanen" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('pembelian.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('pembelian.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pembelian Panen
                    </a>
                    <a href="{{ route('penjualan-buah.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('penjualan-buah.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penjualan Panen
                    </a>
                    <a href="{{ route('koperasi.penawaran-panen.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.penawaran-panen.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penawaran Masuk
                    </a>
                </div>

                <a href="{{ route('koperasi.gudang-stok.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('koperasi.gudang-stok.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span class="font-semibold text-sm">Gudang Koperasi</span>
                </a>
                <a href="{{ route('koperasi.stok-koperasi.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('koperasi.stok-koperasi.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    <span class="font-semibold text-sm">Stok Koperasi</span>
                </a>
                <a href="{{ route('koperasi.atur-harga-pasar.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('koperasi.atur-harga-pasar.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0V8" /></svg>
                    <span class="font-semibold text-sm">Atur Harga Pasar</span>
                </a>
                <button type="button" @click="openPembayaranKoperasi = !openPembayaranKoperasi"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isKoperasiPembayaranActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-2">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        <span class="font-semibold text-sm">Pembayaran Koperasi</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openPembayaranKoperasi ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openPembayaranKoperasi" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('pembayaran.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ (request()->routeIs('pembayaran.index') && !request()->has('view')) ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pembelian Panen
                    </a>
                    <a href="{{ route('koperasi.pembayaran.penjualan') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.pembayaran.penjualan') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penjualan Buah
                    </a>
                    <a href="{{ route('koperasi.pembayaran.distribusi') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.pembayaran.distribusi') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Distribusi Benih
                    </a>
                </div>

                <!-- Collapsible Laporan Koperasi -->
                <button type="button" @click="openLaporan = !openLaporan"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isKoperasiLaporanActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-2">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-semibold text-sm">Laporan Koperasi</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openLaporan ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openLaporan" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('koperasi.laporan.pengajuan-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.laporan.pengajuan-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pengajuan Benih
                    </a>
                    <a href="{{ route('koperasi.laporan.distribusi-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.laporan.distribusi-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Distribusi Benih
                    </a>
                    <a href="{{ route('koperasi.laporan.penawaran-panen') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.laporan.penawaran-panen') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penawaran Panen
                    </a>
                    <a href="{{ route('koperasi.laporan.pembelian') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.laporan.pembelian') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pembelian Panen
                    </a>
                    <a href="{{ route('koperasi.laporan.pembayaran') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('koperasi.laporan.pembayaran') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pembayaran Keluar
                    </a>
                </div>
            </div>
            @endif

            <!-- Group 3: MANAJEMEN PETANI -->
            @if($isAdmin || $isPetani)
            @php
                $isPetaniBenihActive = request()->routeIs('pengajuan-benih.petani') || request()->routeIs('penanaman.*') || request()->routeIs('distribusi-benih.index');
                $isPetaniRiwayatActive = request()->routeIs('petani.layanan.*');
                $isPetaniPanenActive = request()->routeIs('panen.*') || request()->routeIs('stok.*') || request()->routeIs('petani.penawaran-panen.*');
                $isPetaniLaporanActive = request()->routeIs('petani.laporan.*');
                $isPetaniPembayaranActive = request()->get('view') === 'petani' || request()->routeIs('petani.pembayaran.penjualan') || request()->routeIs('petani.pembayaran.distribusi');
            @endphp
            <div x-data="{ open: {{ $isPetaniBenihActive ? 'true' : 'false' }}, openRiwayat: {{ $isPetaniRiwayatActive ? 'true' : 'false' }}, openPanenPetani: {{ $isPetaniPanenActive ? 'true' : 'false' }}, openLaporan: {{ $isPetaniLaporanActive ? 'true' : 'false' }}, openPembayaranPetani: {{ $isPetaniPembayaranActive ? 'true' : 'false' }} }">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">3. Manajemen Petani</p>
                
                <button type="button" @click="openPanenPetani = !openPanenPetani"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isPetaniPanenActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-1">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span class="font-semibold text-sm">Kelola Panen Petani</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openPanenPetani ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openPanenPetani" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('panen.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('panen.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Hasil Panen
                    </a>
                    <a href="{{ route('stok.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('stok.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Stok Siap Jual
                    </a>
                    <a href="{{ route('petani.penawaran-panen.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.penawaran-panen.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penawaran Penjualan
                    </a>
                </div>
                
                <!-- Collapsible Layanan Benih -->
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isPetaniBenihActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        <span class="font-semibold text-sm">Layanan Benih</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('pengajuan-benih.petani') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('pengajuan-benih.petani') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pengajuan Benih
                    </a>
                    <a href="{{ route('penanaman.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('penanaman.*') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penanaman Benih
                    </a>
                    <a href="{{ route('distribusi-benih.index') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('distribusi-benih.index') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Distribusi / Pembelian Benih
                    </a>
                </div>

                <!-- Collapsible Riwayat Layanan Petani -->
                <button type="button" @click="openRiwayat = !openRiwayat"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isPetaniRiwayatActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-1">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-semibold text-sm">Riwayat Layanan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openRiwayat ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openRiwayat" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('petani.layanan.riwayat-pengajuan-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.layanan.riwayat-pengajuan-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Pengajuan Benih
                    </a>
                    <a href="{{ route('petani.layanan.riwayat-distribusi-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.layanan.riwayat-distribusi-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Distribusi Benih
                    </a>
                    <a href="{{ route('petani.layanan.riwayat-penawaran-panen') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.layanan.riwayat-penawaran-panen') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Penawaran Panen
                    </a>
                    <a href="{{ route('petani.layanan.riwayat-pembelian') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.layanan.riwayat-pembelian') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Pembelian
                    </a>
                    <a href="{{ route('petani.layanan.riwayat-penjualan') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.layanan.riwayat-penjualan') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Penjualan
                    </a>
                    <a href="{{ route('petani.layanan.riwayat-pembayaran') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.layanan.riwayat-pembayaran') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Riwayat Pembayaran
                    </a>
                </div>

                <!-- Collapsible Kelola Panen Petani -->
                
                <a href="{{ route('petani-gudang.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('petani-gudang.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span class="font-semibold text-sm">Gudang Petani</span>
                </a>
                <a href="{{ route('atur-harga.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('atur-harga.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0V8" /></svg>
                    <span class="font-semibold text-sm">Atur Harga Petani</span>
                </a>
                <a href="{{ route('metode-pembayaran.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('metode-pembayaran.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    <span class="font-semibold text-sm">Metode Pembayaran</span>
                </a>
                <button type="button" @click="openPembayaranPetani = !openPembayaranPetani"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isPetaniPembayaranActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-2">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        <span class="font-semibold text-sm">Pembayaran Petani</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openPembayaranPetani ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openPembayaranPetani" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('pembayaran.index', ['view' => 'petani']) }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->get('view') === 'petani' ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penjualan Panen
                    </a>
                    <a href="{{ route('petani.pembayaran.penjualan') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.pembayaran.penjualan') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pembelian Buah
                    </a>
                    <a href="{{ route('petani.pembayaran.distribusi') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.pembayaran.distribusi') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Tagihan Benih
                    </a>
                </div>

                <!-- Collapsible Laporan Petani -->
                <button type="button" @click="openLaporan = !openLaporan"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl {{ $isPetaniLaporanActive ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50' }} transition mt-2">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-semibold text-sm">Laporan Petani</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="openLaporan ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openLaporan" x-cloak class="pl-4 mt-1 space-y-1">
                    <a href="{{ route('petani.laporan.pengajuan-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.laporan.pengajuan-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pengajuan Benih
                    </a>
                    <a href="{{ route('petani.laporan.distribusi-benih') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.laporan.distribusi-benih') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Distribusi Benih
                    </a>
                    <a href="{{ route('petani.laporan.penawaran-panen') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.laporan.penawaran-panen') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penawaran Panen
                    </a>
                    <a href="{{ route('petani.laporan.pembelian') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.laporan.pembelian') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Pembelian Benih
                    </a>
                    <a href="{{ route('petani.laporan.penjualan') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.laporan.penjualan') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Penjualan Panen
                    </a>
                    <a href="{{ route('petani.laporan.pembayaran') }}"
                        class="block px-4 py-2 text-sm rounded-xl transition {{ request()->routeIs('petani.laporan.pembayaran') ? 'bg-[#001842] text-white font-bold' : 'text-slate-500 hover:bg-slate-50' }}">
                        - Dana Diterima
                    </a>
                </div>
            </div>
            @endif

            <!-- Group 4: SISTEM & LAPORAN -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sistem & Laporan</p>
                @if($isAdmin)
                <a href="{{ route('pengguna.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pengguna.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="font-semibold text-sm">Pengguna (Hak Akses)</span>
                </a>
                @endif
                <a href="{{ route('daftar-transaksi.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('daftar-transaksi.*') || request()->routeIs('pembayaran.invoice') || request()->routeIs('pembayaran.struk') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    <span class="font-semibold text-sm">Daftar Transaksi</span>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('laporan.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                    <span class="font-semibold text-sm">Laporan</span>
                </a>
                <a href="{{ route('pengaturan.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('pengaturan.*') ? 'bg-[#001842] text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
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
    <div class="flex-1 flex flex-col min-w-0 w-full">
        <header class="bg-white border-b px-4 lg:px-8 py-4 flex gap-4 lg:gap-0 justify-between items-center sticky top-0 z-30">
            <div class="flex items-center gap-4 flex-1">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="relative w-full max-w-[200px] lg:max-w-xs hidden sm:block">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari data di aplikasi...">
                </div>
            </div>
            <div class="flex items-center gap-6">
                @php
                    $unreadNotifCount = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count();
                    $unreadNotifCount += \App\Models\Notifikasi::getSystemAlerts()->count();
                @endphp
                <a href="{{ route('notifikasi.index') }}" class="relative p-2 text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    @if($unreadNotifCount > 0)
                        <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full">
                            {{ $unreadNotifCount }}
                        </span>
                    @endif
                </a>
                <div class="flex items-center gap-3 pl-4 lg:pl-6 border-l">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">
                            {{ auth()->user()->name ?? 'Pengguna' }}
                        </p>
                        <span
                            class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded uppercase font-bold tracking-wider">
                            {{ auth()->user()->role ?? 'Admin, Petani, Koperasi, Superadmin' }}
                        </span>
                    </div>
                    <img src="https://i.pravatar.cc/40" class="w-8 h-8 lg:w-10 lg:h-10 rounded-full" alt="Admin">
                </div>
            </div>
        </header>
        <main class="p-4 lg:p-8 overflow-x-hidden">@yield('content')</main>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('confirm')) {
                    const match = onsubmitAttr.match(/confirm\(['"]([^'"]+)['"]\)/);
                    const message = match ? match[1] : 'Apakah Anda yakin ingin melanjutkan?';
                    
                    form.removeAttribute('onsubmit');
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#94a3b8',
                            confirmButtonText: 'Ya, Lanjutkan!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                confirmButton: 'px-4 py-2 bg-rose-500 text-white rounded-lg font-bold',
                                cancelButton: 'px-4 py-2 bg-slate-400 text-white rounded-lg font-bold'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });
        });
    </script>
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