<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MetodePembayaran::with('user');
        
        if (Auth::user()->role === 'petani') {
            $query->where('user_id', Auth::id());
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $methods = $query->latest()->paginate(5);
        return view('petani.metode_pembayaran.index', compact('methods'));
    }

    public function create()
    {
        $petanis = \App\Models\User::where('role', 'petani')->get();
        return view('petani.metode_pembayaran.create', compact('petanis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'required|string|in:Transfer Bank,E-Wallet,QRIS,Virtual Account,Tunai / Cash,Kartu Kredit / Debit',
            'bank' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:50',
            'qr_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')->store('qr_images', 'public');
        }

        MetodePembayaran::create(array_merge($data, ['user_id' => Auth::id()]));

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return redirect()->route('metode-pembayaran.index');
    }

    public function edit(string $id)
    {
        $method = MetodePembayaran::where('user_id', Auth::id())->findOrFail($id);
        $petanis = \App\Models\User::where('role', 'petani')->get();
        return view('petani.metode_pembayaran.edit', compact('method', 'petanis'));
    }

    public function update(Request $request, string $id)
    {
        $method = MetodePembayaran::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'kategori' => 'required|string|in:Transfer Bank,E-Wallet,QRIS,Virtual Account,Tunai / Cash,Kartu Kredit / Debit',
            'bank' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:50',
            'qr_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('qr_image')) {
            if ($method->qr_image) {
                Storage::disk('public')->delete($method->qr_image);
            }
            $data['qr_image'] = $request->file('qr_image')->store('qr_images', 'public');
        }

        $method->update($data);

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            $method = MetodePembayaran::findOrFail($id);
            
            DB::transaction(function () use ($method) {
                \App\Models\Pembayaran::where('metode_pembayaran_id', $method->id)->delete();
                
                if ($method->qr_image) {
                    Storage::disk('public')->delete($method->qr_image);
                }
                
                $method->delete();
            });

            return redirect()->back()->with('success', 'Metode pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus metode pembayaran: ' . $e->getMessage());
        }
    }
}
