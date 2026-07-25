@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>Konfigurasi Profil & Hak Akses</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Pengaturan Sistem & Profil</h1>
            <p class="text-slate-300 text-sm max-w-xl">
                Halo, <span class="font-extrabold text-white">{{ auth()->user()->name }}</span>! Kelola informasi profil diri, nomor telepon, alamat, password, serta matriks peranan aplikasi.
            </p>
        </div>

        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-white/10 border border-white/15 text-white text-xs font-bold backdrop-blur-md">
                Role Anda: 
                @if(auth()->user()->role === 'super admin') 👑 Super Admin
                @elseif(auth()->user()->role === 'admin') 🛡️ Admin
                @elseif(auth()->user()->role === 'koperasi') 🏢 Koperasi
                @elseif(auth()->user()->role === 'petani') 🌾 Petani
                @else 🛒 Konsumen @endif
            </span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" /> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-4 text-sm font-semibold text-rose-800 shadow-sm">
            <x-heroicon-o-x-circle class="h-5 w-5 text-rose-600" /> {{ session('error') }}
        </div>
    @endif

    <!-- FORM EDIT PROFIL & DATA DIRI SAYA -->
    <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-xl shadow-slate-100/60 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-indigo-600 via-blue-600 to-teal-500 absolute top-0 left-0"></div>

        <div class="flex items-center justify-between border-b border-slate-100 pb-6 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-extrabold flex items-center justify-center shadow-lg shadow-indigo-500/30 text-base">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">Profil & Data Diri Saya</h2>
                    <p class="text-xs text-slate-400 font-medium">Perbarui informasi profil akun dan kata sandi Anda sendiri</p>
                </div>
            </div>
            <span class="text-xs font-bold font-mono text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">ID: #{{ auth()->id() }}</span>
        </div>

        <form action="{{ route('pengaturan.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" required>
                    @error('name')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" required>
                    @error('email')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No. Telp / HP -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon / HP</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', auth()->user()->no_telp) }}" placeholder="Contoh: 081234567890" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                    @error('no_telp')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Role Akses Badge (Disabled for non-admin) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role Akses Terdaftar</label>
                    <div class="px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 font-bold text-sm flex items-center justify-between">
                        <span>
                            @if(auth()->user()->role === 'super admin') 👑 Super Admin
                            @elseif(auth()->user()->role === 'admin') 🛡️ Admin
                            @elseif(auth()->user()->role === 'koperasi') 🏢 Koperasi
                            @elseif(auth()->user()->role === 'petani') 🌾 Petani
                            @else 🛒 Konsumen @endif
                        </span>
                        <span class="text-xs text-slate-400 font-medium">(Terdaftar di Sistem)</span>
                    </div>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap Domisili / Tempat Tinggal</label>
                <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap Anda..." class="w-full rounded-2xl border border-slate-200 p-4 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                @error('alamat')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Ubah Password (Opsional) -->
            <div class="border-t border-slate-100 pt-6 mt-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200/60">💡 Kosongkan bidang di bawah jika tidak ingin mengubah password akun Anda saat ini.</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                        @error('password')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Action Submit -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition-all">
                    Simpan Perubahan Profil Saya
                </button>
            </div>
        </form>
    </div>

    <!-- MODULE CARDS LINK -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Data Gudang -->
        <a href="{{ route('gudang.index') }}" class="block bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-100/60 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-amber-600 transition-colors">Data Gudang</h3>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">Kelola kapasitas, lokasi, dan informasi detail mengenai gudang penyimpanan kentang.</p>
        </a>

        <!-- Pengguna & Hak Akses (Membuka Popup Modal Detail Informasi) -->
        <div onclick="openRoleModal()" class="block bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-100/60 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer group relative overflow-hidden">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Pengguna & Akses</h3>
                <span class="text-[10px] uppercase font-extrabold tracking-wider bg-indigo-50 text-indigo-700 px-2.5 py-0.5 rounded-full border border-indigo-100">Klik Detail ↗</span>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">Lihat daftar akun pengguna dan matriks hak akses seluruh peranan (role) sistem.</p>
        </div>

        <!-- Preferensi Sistem -->
        <div class="block bg-white rounded-3xl p-6 border border-slate-100 shadow-sm opacity-60 cursor-not-allowed">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-lg font-bold text-slate-800">Preferensi Sistem</h3>
                <span class="text-[10px] uppercase font-bold tracking-wider bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full">Segera</span>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">Pengaturan preferensi antarmuka, bahasa, zona waktu, dan parameter aplikasi lainnya.</p>
        </div>

    </div>
</div>

<!-- POPUP MODAL INFORMASI HAK AKSES & ROLE PENGGUNA -->
<div id="roleInfoModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl rounded-3xl bg-white p-6 md:p-8 shadow-2xl border border-slate-100 space-y-6 transform transition-all">
        
        <!-- Header Modal -->
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-indigo-50 text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800">Detail Hak Akses & Role Pengguna</h3>
                    <p class="text-xs text-slate-400 font-medium">Informasi matriks peranan dan wewenang akun pengguna sistem Smart Agroindustri</p>
                </div>
            </div>
            <button onclick="closeRoleModal()" class="p-2 rounded-2xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-all">
                <x-heroicon-o-x-mark class="w-5 h-5"/>
            </button>
        </div>

        <!-- Matriks Role List -->
        <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
            
            <!-- Super Admin -->
            <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 flex items-start gap-3">
                <span class="p-2.5 rounded-xl bg-purple-100 text-purple-700 font-bold text-sm">👑</span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-extrabold text-purple-950 text-sm">Super Admin</h4>
                        <span class="text-[10px] font-bold bg-purple-200/80 text-purple-800 px-2 py-0.5 rounded-full">Akses Penuh</span>
                    </div>
                    <p class="text-xs text-purple-900 font-medium">Memiliki wewenang tertinggi pada seluruh modul, pengelolaan master data, manajemen pengguna, serta konfigurasi otorisasi sistem.</p>
                </div>
            </div>

            <!-- Admin -->
            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-start gap-3">
                <span class="p-2.5 rounded-xl bg-blue-100 text-blue-700 font-bold text-sm">🛡️</span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-extrabold text-blue-950 text-sm">Admin</h4>
                        <span class="text-[10px] font-bold bg-blue-200/80 text-blue-800 px-2 py-0.5 rounded-full">Manajerial Operasional</span>
                    </div>
                    <p class="text-xs text-blue-900 font-medium">Mengelola data gudang, pencatatan panen, stok, transaksi pembelian & pembayaran, serta menambah/mengedit pengguna.</p>
                </div>
            </div>

            <!-- Koperasi -->
            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100 flex items-start gap-3">
                <span class="p-2.5 rounded-xl bg-amber-100 text-amber-700 font-bold text-sm">🏢</span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-extrabold text-amber-950 text-sm">Koperasi</h4>
                        <span class="text-[10px] font-bold bg-amber-200/80 text-amber-800 px-2 py-0.5 rounded-full">Pengadaan & Pembayaran</span>
                    </div>
                    <p class="text-xs text-amber-900 font-medium">Melakukan transaksi pembelian komoditas kentang dari petani, pembayaran via Midtrans/Bank/Direct, serta mencetak invoice dan struk resmi.</p>
                </div>
            </div>

            <!-- Petani -->
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-start gap-3">
                <span class="p-2.5 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-sm">🌾</span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-extrabold text-emerald-950 text-sm">Petani</h4>
                        <span class="text-[10px] font-bold bg-emerald-200/80 text-emerald-800 px-2 py-0.5 rounded-full">Produsen & Pemasok</span>
                    </div>
                    <p class="text-xs text-emerald-900 font-medium">Mengelola kapasitas gudang penyimpan, mencatat hasil panen kentang, memperbarui stok fisik/siap jual, serta mendaftarkan metode pembayaran bank & QRIS.</p>
                </div>
            </div>

            <!-- Konsumen -->
            <div class="p-4 rounded-2xl bg-teal-50/70 border border-teal-100 flex items-start gap-3">
                <span class="p-2.5 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm">🛒</span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-extrabold text-teal-950 text-sm">Konsumen</h4>
                        <span class="text-[10px] font-bold bg-teal-200/80 text-teal-800 px-2 py-0.5 rounded-full">Peninjau & Pembeli</span>
                    </div>
                    <p class="text-xs text-teal-900 font-medium">Meninjau ketersediaan stok kentang siap jual, informasi harga pasar komoditas, serta peranan hak akses pengguna terdaftar.</p>
                </div>
            </div>

        </div>

        <!-- Footer Modal Buttons -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <button onclick="closeRoleModal()" class="rounded-xl border border-slate-200 px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                Tutup
            </button>
            <a href="{{ route('pengguna.index') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5">
                <span>Buka Daftar Pengguna</span> ↗
            </a>
        </div>
    </div>
</div>

<script>
    function openRoleModal() {
        document.getElementById('roleInfoModal').classList.remove('hidden');
    }

    function closeRoleModal() {
        document.getElementById('roleInfoModal').classList.add('hidden');
    }
</script>
@endsection
