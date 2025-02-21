<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WiFi;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage).
     */
    public function index()
    {
    $wifi = WiFi::where('status_validasi', 'Disetujui')->get();  
    return view('index', compact('wifi'));// Pastikan file resources/views/index.blade.php ada
    }

    /**
     * Menampilkan halaman dashboard setelah login.
     */
    public function home()
    {
        $user = Auth::user();

        // Redirect sesuai role
        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard'); // Pastikan file resources/views/dashboard.blade.php ada
    }
}
