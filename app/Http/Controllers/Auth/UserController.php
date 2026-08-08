<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        // Search filter for all users
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Role filter for all users
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Everyone can see all users & their roles
        $users = $query->get();

        // Counter stats
        $totalUsers = User::count();
        $totalPetani = User::where('role', 'petani')->count();
        $totalKoperasi = User::where('role', 'koperasi')->count();
        $totalKonsumen = User::where('role', 'konsumen')->count();
        $totalAdmin = User::whereIn('role', ['admin', 'super admin'])->count();

        return view('admin.pengguna.index', compact(
            'users', 
            'totalUsers', 
            'totalPetani', 
            'totalKoperasi', 
            'totalKonsumen', 
            'totalAdmin'
        ));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super admin'])) {
            return redirect()->route('pengguna.index')->with('error', 'Akses ditolak. Penambahan pengguna baru hanya dapat dilakukan oleh Admin atau Super Admin.');
        }

        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super admin'])) {
            return redirect()->route('pengguna.index')->with('error', 'Akses ditolak. Penambahan pengguna baru hanya dapat dilakukan oleh Admin atau Super Admin.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:1000',
            'role' => ['required', Rule::in(['admin', 'petani', 'koperasi', 'super admin', 'konsumen'])],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        // Non-admin / non-superadmin cannot edit anyone (Read-only mode for information only)
        if (!in_array(auth()->user()->role, ['admin', 'super admin'])) {
            return redirect()->route('pengguna.index')->with('error', 'Akses ditolak. Anda hanya diperbolehkan melihat informasi pengguna dan peranan hak aksesnya.');
        }

        $user = User::findOrFail($id);
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        // Non-admin / non-superadmin cannot edit anyone
        if (!in_array(auth()->user()->role, ['admin', 'super admin'])) {
            return redirect()->route('pengguna.index')->with('error', 'Akses ditolak. Pengeditan pengguna terbatas hanya untuk Admin dan Super Admin.');
        }

        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_telp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:1000',
            'role' => ['required', Rule::in(['admin', 'petani', 'koperasi', 'super admin', 'konsumen'])],
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Enforce role authorization: Only admin or super admin can delete users
        if (!in_array(auth()->user()->role, ['admin', 'super admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Role Admin atau Super Admin yang dapat menghapus data pengguna.');
        }

        $user = User::findOrFail($id);
        
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            DB::transaction(function () use ($user) {
                // Delete dependent child records
                \App\Models\MetodePembayaran::where('user_id', $user->id)->delete();
                
                $pembelians = \App\Models\Pembelian::where('petani_id', $user->id)->orWhere('koperasi_id', $user->id)->get();
                foreach ($pembelians as $pem) {
                    \App\Models\Pembayaran::where('pembelian_id', $pem->id)->delete();
                    $pem->delete();
                }

                $user->delete();
            });

            return redirect()->back()->with('success', 'Pengguna beserta data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
