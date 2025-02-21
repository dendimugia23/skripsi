<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WiFi;

class UserController extends Controller
{
    /**
     * Menampilkan peta WiFi yang sudah disetujui.
     */
    public function userMap()
{
    // Ambil semua data WiFi yang sudah disetujui
    $wifi = WiFi::where('status_validasi', 'Disetujui')->get();

    // Kirim data WiFi ke tampilan user
    return view('user.map', compact('wifi'));
}

}
