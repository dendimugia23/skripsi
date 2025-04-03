<?php

namespace App\Http\Controllers;

use App\Models\WiFi;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $wifi = WiFi::all(); // Ambil semua data WiFi
        $pengaduan = Pengaduan::all(); // Ambil semua data pengaduan

        return view('admin.dashboard', compact('wifi', 'pengaduan'));
    }
}
