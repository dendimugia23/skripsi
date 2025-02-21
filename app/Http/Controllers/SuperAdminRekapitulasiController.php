<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminRekapitulasiController extends Controller
{
    public function index()
    {
        return view('superadmin.rekapitulasi'); // Pastikan file pengaduan.blade.php ada di resources/views/admin/
    }
}
