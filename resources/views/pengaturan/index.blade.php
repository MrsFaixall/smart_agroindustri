@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Sistem</h1>
        <p class="text-slate-500 text-sm">Kelola data master, pengguna, dan preferensi aplikasi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Data Gudang -->
        <a href="{{ route('gudang.index') }}" class="block bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Data Gudang</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Kelola kapasitas, lokasi, dan informasi detail mengenai gudang penyimpanan kentang.</p>
        </a>

        <!-- Manajemen Pengguna -->
        <a href="{{ route('pengguna.index') }}" class="block bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Pengguna & Akses</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Kelola akun, role (Admin, Petani, Koperasi, Super Admin) beserta hak aksesnya.</p>
        </a>

        <!-- Preferensi Sistem -->
        <div class="block bg-white rounded-3xl p-6 border border-slate-100 shadow-sm opacity-60 cursor-not-allowed">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-lg font-bold text-slate-900">Preferensi Sistem</h3>
                <span class="text-[10px] uppercase font-bold tracking-wider bg-slate-200 text-slate-500 px-2 py-0.5 rounded">Segera</span>
            </div>
            <p class="text-sm text-slate-500 leading-relaxed">Pengaturan preferensi antarmuka, bahasa, zona waktu, dan parameter aplikasi lainnya.</p>
        </div>

    </div>
</div>
@endsection
