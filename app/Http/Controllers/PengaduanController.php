<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /**
     * Menampilkan halaman daftar pengaduan.
     */
    public function index()
    {
        return view('admin.pengaduan'); // Pastikan file pengaduan.blade.php ada di resources/views/admin/
    }
}
