<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WiFi;
use Illuminate\Support\Facades\Log;

class SuperAdminPetaController extends Controller
{
    /**
     * Middleware untuk memastikan hanya superadmin yang dapat mengakses controller ini.
     */
    

    /**
     * Menampilkan daftar WiFi yang perlu divalidasi.
     */
    public function index()
{
    try {
        // Ambil data WiFi dengan status validasi yang bukan 'Ditolak' dan urutkan berdasarkan yang terbaru dengan pagination
        $wifi = WiFi::where('status_validasi', '!=', 'Ditolak')
                    ->latest() // Alternatif lebih ringkas dari orderBy('created_at', 'desc')
                    ->paginate(10); // Gunakan pagination untuk menghindari error

        return view('superadmin.peta', compact('wifi'));
    } catch (\Exception $e) {
        Log::error('Error fetching WiFi data for validation: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat mengambil data WiFi.');
    }
}
    /**
     * Proses validasi WiFi oleh superadmin.
     */
    public function validasi(Request $request, $id)
    {
        // Validasi input request
        $request->validate([
            'status_validasi' => 'required|in:Disetujui,Ditolak',
        ]);

        try {
            // Cari data WiFi berdasarkan ID, jika tidak ditemukan akan otomatis 404
            $wifi = WiFi::findOrFail($id);

            // Cek apakah status validasi masih 'Pending'
            if ($wifi->status_validasi !== 'Pending') {
                return back()->with('error', 'WiFi ini sudah divalidasi sebelumnya.');
            }

            // Update status validasi
            $wifi->update(['status_validasi' => $request->status_validasi]);

            return back()->with('success', 'Status validasi WiFi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating WiFi validation status: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status validasi.');
        }
        
    }

    public function map()
{
    // Ambil semua data WiFi yang tidak memiliki status 'Ditolak'
    $wifi = WiFi::where('status_validasi', '!=', 'Ditolak')->get();
    return view('superadmin.map', compact('wifi'));
}

}
