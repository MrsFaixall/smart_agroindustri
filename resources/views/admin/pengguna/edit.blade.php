@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('pengguna.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5"/>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Profil Pengguna</h1>
            <p class="text-xs text-slate-400">Perbarui informasi profil dan kata sandi akun.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 p-8 relative overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-indigo-600 to-blue-600 absolute top-0 left-0"></div>
        <form action="{{ route('pengguna.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" required>
                    @error('name')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" required>
                    @error('email')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No. Telp / HP -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon / HP</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" placeholder="Contoh: 081234567890" class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-mono text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                    @error('no_telp')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Role Akses -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role Akses Sistem <span class="text-rose-500">*</span></label>
                    @if(in_array(auth()->user()->role, ['admin', 'super admin']))
                        <select name="role" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none font-semibold" required>
                            <option value="petani" {{ old('role', $user->role) == 'petani' ? 'selected' : '' }}>🌾 Petani</option>
                            <option value="koperasi" {{ old('role', $user->role) == 'koperasi' ? 'selected' : '' }}>🏢 Koperasi</option>
                            <option value="konsumen" {{ old('role', $user->role) == 'konsumen' ? 'selected' : '' }}>🛒 Konsumen</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                            <option value="super admin" {{ old('role', $user->role) == 'super admin' ? 'selected' : '' }}>👑 Super Admin</option>
                        </select>
                    @else
                        <div class="px-4 py-3 rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-sm flex items-center justify-between">
                            <span>
                                @if($user->role === 'super admin') 👑 Super Admin
                                @elseif($user->role === 'admin') 🛡️ Admin
                                @elseif($user->role === 'konsumen') 🛒 Konsumen
                                @elseif($user->role === 'koperasi') 🏢 Koperasi
                                @else 🌾 Petani @endif
                            </span>
                            <span class="text-xs text-slate-400 font-normal">(Tidak dapat diubah)</span>
                        </div>
                    @endif
                    @error('role')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap domisili / kantor..." class="w-full rounded-2xl border border-slate-200 p-4 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">{{ old('alamat', $user->alamat) }}</textarea>
                @error('alamat')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Password Baru (Opsional) -->
            <div class="border-t border-slate-100 pt-6 mt-6">
                <p class="text-xs text-amber-700 mb-4 bg-amber-50/80 px-4 py-2.5 rounded-2xl border border-amber-200/60 font-semibold inline-block">💡 Biarkan kosong jika tidak ingin mengubah password saat ini.</p>
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

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('pengguna.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition-all">Perbarui Profil</button>
            </div>
        </form>
    </div>
</div>
@endsection
