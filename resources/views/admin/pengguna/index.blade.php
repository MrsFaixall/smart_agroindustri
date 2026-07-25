@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner Gradient -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-200/50 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 right-1/3 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-1 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>Daftar Hak Akses & Peranan Pengguna</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen Pengguna</h1>
            <p class="text-slate-300 text-sm max-w-xl">
                Daftar lengkap seluruh pengguna terdaftar beserta hak akses dan peranan (role) sesi akun dalam aplikasi.
            </p>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'super admin']))
            <div class="relative z-10">
                <a href="{{ route('pengguna.create') }}" class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all duration-200 text-sm font-bold shadow-lg shadow-indigo-600/30 transform hover:-translate-y-0.5">
                    <span class="text-lg leading-none">+</span> Tambah Pengguna Baru
                </a>
            </div>
        @endif
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

    @if(!in_array(auth()->user()->role, ['admin', 'super admin']))
        <div class="flex items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50/80 px-5 py-4 text-sm font-semibold text-indigo-950 shadow-sm">
            <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>ℹ️ Mode Informasi (Read-Only): Anda dapat melihat daftar seluruh pengguna dan peranan (role) hak aksesnya. Pengeditan & pengubahan data pengguna terbatas khusus untuk Admin & Super Admin.</span>
        </div>
    @endif

    <!-- KPI STATS PER ROLE SESSION -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4">
        <!-- Total -->
        <a href="{{ route('pengguna.index') }}" class="bg-gradient-to-br from-slate-50/90 via-white to-slate-100/50 border border-slate-200 p-4 rounded-3xl shadow-sm hover:shadow-md transition-all duration-200 group flex items-center gap-3">
            <div class="p-3 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white shadow-md group-hover:scale-105 transition-transform">
                <x-heroicon-o-users class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total User</p>
                <h3 class="text-xl font-extrabold text-slate-800">{{ number_format($totalUsers ?? 0) }}</h3>
            </div>
        </a>

        <!-- Petani -->
        <a href="{{ route('pengguna.index', ['role' => 'petani']) }}" class="bg-gradient-to-br from-emerald-50/80 via-white to-teal-50/40 border border-emerald-100 p-4 rounded-3xl shadow-sm hover:shadow-md transition-all duration-200 group flex items-center gap-3">
            <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                <x-heroicon-o-user-group class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-emerald-800 uppercase">Sesi Petani</p>
                <h3 class="text-xl font-extrabold text-emerald-900">{{ number_format($totalPetani ?? 0) }}</h3>
            </div>
        </a>

        <!-- Koperasi -->
        <a href="{{ route('pengguna.index', ['role' => 'koperasi']) }}" class="bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 border border-amber-100 p-4 rounded-3xl shadow-sm hover:shadow-md transition-all duration-200 group flex items-center gap-3">
            <div class="p-3 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
                <x-heroicon-o-building-storefront class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-amber-800 uppercase">Sesi Koperasi</p>
                <h3 class="text-xl font-extrabold text-amber-900">{{ number_format($totalKoperasi ?? 0) }}</h3>
            </div>
        </a>

        <!-- Konsumen -->
        <a href="{{ route('pengguna.index', ['role' => 'konsumen']) }}" class="bg-gradient-to-br from-teal-50/80 via-white to-cyan-50/40 border border-teal-100 p-4 rounded-3xl shadow-sm hover:shadow-md transition-all duration-200 group flex items-center gap-3">
            <div class="p-3 rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-md shadow-teal-500/20 group-hover:scale-105 transition-transform">
                <x-heroicon-o-shopping-cart class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-teal-800 uppercase">Sesi Konsumen</p>
                <h3 class="text-xl font-extrabold text-teal-900">{{ number_format($totalKonsumen ?? 0) }}</h3>
            </div>
        </a>

        <!-- Admin / Super Admin -->
        <a href="{{ route('pengguna.index', ['role' => 'admin']) }}" class="bg-gradient-to-br from-blue-50/80 via-white to-indigo-50/40 border border-blue-100 p-4 rounded-3xl shadow-sm hover:shadow-md transition-all duration-200 group flex items-center gap-3">
            <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                <x-heroicon-o-shield-check class="w-5 h-5"/>
            </div>
            <div>
                <p class="text-[10px] font-bold tracking-wider text-blue-800 uppercase">Admin / Super</p>
                <h3 class="text-xl font-extrabold text-blue-900">{{ number_format($totalAdmin ?? 0) }}</h3>
            </div>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('pengguna.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto flex-1">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" 
                    placeholder="Cari nama, email, no. telp, atau alamat...">
            </div>

            <select name="role" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 rounded-2xl text-xs bg-slate-50/50 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                <option value="">Semua Role Access</option>
                <option value="super admin" {{ request('role') == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="konsumen" {{ request('role') == 'konsumen' ? 'selected' : '' }}>Konsumen</option>
                <option value="koperasi" {{ request('role') == 'koperasi' ? 'selected' : '' }}>Koperasi</option>
                <option value="petani" {{ request('role') == 'petani' ? 'selected' : '' }}>Petani</option>
            </select>

            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 text-xs font-bold rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20">
                Cari
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('pengguna.index') }}" class="w-full sm:w-auto px-3 py-2.5 text-xs font-semibold rounded-2xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">No.</th>
                        <th class="px-6 py-4">Nama Lengkap & Kontak</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4">Role Akses</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors cursor-pointer" onclick="openDetailModal({{ json_encode($user) }})">
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-extrabold flex items-center justify-center shadow-md shadow-indigo-500/20 text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-800 hover:text-indigo-600 transition-colors flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if(auth()->id() === $user->id)
                                                <span class="text-[10px] text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded font-bold">(Anda)</span>
                                            @endif
                                            <span class="text-[10px] text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded font-mono font-bold">Detail ↗</span>
                                        </div>
                                        <div class="text-xs text-slate-400 font-mono flex items-center gap-1 mt-0.5">
                                            <span>📞 {{ $user->no_telp ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium text-xs">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs max-w-xs truncate">
                                {{ $user->alamat ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role === 'super admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                                        👑 Super Admin
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs">
                                        🛡️ Admin
                                    </span>
                                @elseif($user->role === 'konsumen')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200 shadow-2xs">
                                        🛒 Konsumen
                                    </span>
                                @elseif($user->role === 'koperasi')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                                        🏢 Koperasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                        🌾 Petani
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" onclick="openDetailModal({{ json_encode($user) }})" class="rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">
                                        Detail
                                    </button>

                                    <!-- Tombol Edit & Hapus HANYA untuk Admin & Super Admin -->
                                    @if(in_array(auth()->user()->role, ['admin', 'super admin']))
                                        <a href="{{ route('pengguna.edit', $user->id) }}" class="rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition-colors">
                                            Edit Profil
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna {{ $user->name }} beserta seluruh data terkait?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                Belum ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL PENGGUNA -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 md:p-8 shadow-2xl border border-slate-100 space-y-6 transform transition-all">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div id="modalAvatar" class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-extrabold flex items-center justify-center shadow-lg shadow-indigo-500/30 text-base">
                    US
                </div>
                <div>
                    <h3 id="modalName" class="text-lg font-bold text-slate-800">Nama Pengguna</h3>
                    <div id="modalRoleBadge" class="mt-0.5"></div>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="p-2 rounded-2xl bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-all">
                <x-heroicon-o-x-mark class="w-5 h-5"/>
            </button>
        </div>

        <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-3">
                <div class="flex items-start gap-3">
                    <span class="p-2 rounded-xl bg-blue-50 text-blue-600 font-bold text-xs">✉️</span>
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Email</span>
                        <span id="modalEmail" class="text-sm font-bold text-slate-800 font-mono">-</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 pt-2 border-t border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 font-bold text-xs">📞</span>
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Nomor Telepon / HP</span>
                        <span id="modalTelp" class="text-sm font-bold text-slate-800 font-mono">-</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 pt-2 border-t border-slate-100">
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600 font-bold text-xs">📍</span>
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Lengkap</span>
                        <p id="modalAlamat" class="text-xs font-semibold text-slate-700 leading-relaxed">-</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">Tanggal Terdaftar</span>
                    <span id="modalCreated" class="font-bold text-slate-800 font-mono mt-0.5 block">-</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 block text-[10px] font-bold uppercase tracking-wider">ID Pengguna</span>
                    <span id="modalId" class="font-bold text-indigo-700 font-mono mt-0.5 block">#0</span>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-[11px] text-slate-400">Hak Akses Terdaftar pada Database</span>
            <button onclick="closeDetailModal()" class="rounded-xl bg-slate-900 text-white px-5 py-2.5 text-xs font-bold hover:bg-slate-800 transition-all shadow-md">
                Tutup Detail
            </button>
        </div>
    </div>
</div>

<script>
    function openDetailModal(user) {
        document.getElementById('modalName').textContent = user.name || '-';
        document.getElementById('modalEmail').textContent = user.email || '-';
        document.getElementById('modalTelp').textContent = user.no_telp || 'Tidak dicantumkan';
        document.getElementById('modalAlamat').textContent = user.alamat || 'Alamat belum diisi.';
        document.getElementById('modalId').textContent = '#' + user.id;

        if (user.created_at) {
            const date = new Date(user.created_at);
            document.getElementById('modalCreated').textContent = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        } else {
            document.getElementById('modalCreated').textContent = '-';
        }

        const initials = (user.name || 'US').substring(0, 2).toUpperCase();
        document.getElementById('modalAvatar').textContent = initials;

        const roleBadge = document.getElementById('modalRoleBadge');
        let badgeHtml = '';
        if (user.role === 'super admin') {
            badgeHtml = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">👑 Super Admin</span>';
        } else if (user.role === 'admin') {
            badgeHtml = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">🛡️ Admin</span>';
        } else if (user.role === 'konsumen') {
            badgeHtml = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">🛒 Konsumen</span>';
        } else if (user.role === 'koperasi') {
            badgeHtml = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">🏢 Koperasi</span>';
        } else {
            badgeHtml = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">🌾 Petani</span>';
        }
        roleBadge.innerHTML = badgeHtml;

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection
