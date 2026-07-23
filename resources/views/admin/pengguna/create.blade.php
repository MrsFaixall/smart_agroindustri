@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('pengguna.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Tambah Pengguna</h1>
            <p class="text-slate-500 text-sm">Tambahkan pengguna baru ke dalam sistem.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('pengguna.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                    @error('name')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                    @error('email')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Role Akses</label>
                <select name="role" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                    <option value="">Pilih Role</option>
                    <option value="super admin" {{ old('role') == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="koperasi" {{ old('role') == 'koperasi' ? 'selected' : '' }}>Koperasi</option>
                    <option value="petani" {{ old('role') == 'petani' ? 'selected' : '' }}>Petani</option>
                </select>
                @error('role')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                    @error('password')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#001842] focus:ring-[#001842] transition-colors" required>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('pengguna.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#001842] text-white font-semibold hover:bg-[#002a70] transition-colors">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
