<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengaduanController extends Controller
{
    /**
     * Menampilkan halaman daftar pengaduan dengan data dari database.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pengaduan = Pengaduan::when($search, function ($query, $search) {
            return $query->where('ticket_number', 'like', "%$search%")
                         ->orWhere('nama_wifi', 'like', "%$search%")
                         ->orWhere('kategori_pengaduan', 'like', "%$search%");
        })->paginate(10);

        return view('admin.pengaduan', compact('pengaduan'));
    }

    /**
     * Memvalidasi pengaduan dan mengubah status menjadi 'Tervalidasi'.
     */
    public function validasi(Request $request, $id)
    {
        // Validasi input request
        $request->validate([
            'status_pengaduan' => 'required|in:Tervalidasi',
        ]);

        try {
            // Cari data pengaduan berdasarkan ID, jika tidak ditemukan akan otomatis 404
            $pengaduan = Pengaduan::findOrFail($id);

            // Cek apakah status validasi masih 'Proses'
            if ($pengaduan->status_pengaduan !== 'Proses') {
                return back()->with('error', 'Pengaduan ini sudah divalidasi sebelumnya.');
            }

            // Update status validasi
            $pengaduan->update(['status_pengaduan' => 'Tervalidasi']);

            return back()->with('success', 'Status validasi pengaduan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating validation status: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status validasi.');
        }
    }
}
