<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $systemAlerts = Notifikasi::getSystemAlerts();
        $notifikasis = Notifikasi::where('user_id', Auth::id())->latest()->paginate(20);
        return view('notifikasi.index', compact('notifikasis', 'systemAlerts'));
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())->findOrFail($id);
        $notifikasi->update(['is_read' => true]);

        if ($notifikasi->url) {
            return redirect($notifikasi->url);
        }

        return back();
    }
}
