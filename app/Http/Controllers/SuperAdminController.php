<?php


namespace App\Http\Controllers;
use App\Models\WiFi;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        $wifi = WiFi::all(); // Ambil semua data WiFi
        $pengaduan = Pengaduan::all(); // Ambil semua data pengaduan
        return view('superadmin.dashboard', compact('wifi', 'pengaduan'));
    }
}
