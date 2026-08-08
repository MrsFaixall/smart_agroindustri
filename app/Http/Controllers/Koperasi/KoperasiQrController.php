<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\PenjualanBuah;
use Illuminate\Support\Facades\Auth;

class KoperasiQrController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $qrTransactions = PenjualanBuah::with(['pembeli', 'jenisKentang', 'koperasi'])
            ->whereNotNull('tracking_token')
            ->when($user->role === 'koperasi', function ($query) use ($user) {
                $query->where('koperasi_id', $user->id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->whereHas('jenisKentang', function($qk) use ($search) {
                        $qk->where('nama_jenis', 'like', "%{$search}%");
                    })->orWhereHas('pembeli', function($qp) use ($search) {
                        $qp->where('name', 'like', "%{$search}%");
                    })->orWhere('tracking_token', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('koperasi.qr-code.index', compact('qrTransactions'));
    }
}
