<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WiFi;
use Illuminate\Support\Facades\Log;

class SuperAdminPetaController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');

            $wifi = WiFi::where('status_validasi', '!=', 'Ditolak')
                ->when($search, function ($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%')
                          ->orWhere('lokasi', 'like', '%' . $search . '%')
                          ->orWhere('ssid', 'like', '%' . $search . '%');
                    });
                })
                ->latest()
                ->paginate(10);

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
        $request->validate([
            'status_validasi' => 'required|in:Disetujui,Ditolak',
            'komentar' => 'nullable|string|max:255',
        ]);

        try {
            $wifi = WiFi::findOrFail($id);

            if ($wifi->status_validasi !== 'Pending') {
                return back()->with('error', 'WiFi ini sudah divalidasi sebelumnya.');
            }

            $wifi->update([
                'status_validasi' => $request->status_validasi,
                'komentar' => $request->status_validasi === 'Ditolak' ? $request->komentar : null,
            ]);

            return back()->with('success', 'Status validasi WiFi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating WiFi validation status: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status validasi.');
        }
    }

    /**
     * Menampilkan peta lokasi WiFi.
     */
    public function map()
    {
        $wifi = WiFi::where('status_validasi', '!=', 'Ditolak')->get();
        return view('superadmin.map', compact('wifi'));
    }
}
