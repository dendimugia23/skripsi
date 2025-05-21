<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WiFi;
use App\Models\Pengaduan;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage).
     */
    public function index()
    {
        $wifi = WiFi::where('status_validasi', 'Disetujui')->get();  
        return view('index', compact('wifi'));
    }

    /**
     * Menangani pencarian tiket pengaduan.
     */
    public function searchPengaduan(Request $request)
{
    $request->validate(['ticket_number' => 'required|string']);

    $ticket = strtolower($request->ticket_number);

    $pengaduan = Pengaduan::whereRaw('LOWER(ticket_number) = ?', [$ticket])->first();

    if (!$pengaduan) {
        return response()->json([
            'success' => false,
            'message' => 'Nomor tiket tidak ditemukan.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'ticket_number' => $pengaduan->ticket_number,
        'status_pengaduan' => $pengaduan->status_pengaduan,
        'description_pengaduan' => $pengaduan->deskripsi
    ]);
}
    

    /**
     * Menampilkan halaman dashboard setelah login.
     */
    public function home()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }
}
