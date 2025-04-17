<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\WiFi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengaduanuserController extends Controller
{
    public function index()
    {
        $wifi = WiFi::where('status_validasi', 'Disetujui')->get();  
        return view('pengaduanuser', compact('wifi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_wifi' => 'required|string|max:255',
            'kategori_pengaduan' => 'required|string',
            'deskripsi_pengaduan' => 'required|string',
            'image_pengaduan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $ticketNumber = 'TKT-' . strtoupper(Str::random(8));

        $pengaduan = new Pengaduan();
        $pengaduan->ticket_number = $ticketNumber;
        $pengaduan->nama_wifi = $request->nama_wifi;
        $pengaduan->kategori_pengaduan = $request->kategori_pengaduan;
        $pengaduan->deskripsi_pengaduan = $request->deskripsi_pengaduan;

        // Simpan gambar pengaduan
        if ($request->hasFile('image_pengaduan')) {
            $imagePath = $request->file('image_pengaduan')->store('pengaduan_images', 'public');
            $pengaduan->image_pengaduan = $imagePath;
        }

        // Simpan gambar KTP
        if ($request->hasFile('image_ktp')) {
            $ktpPath = $request->file('image_ktp')->store('ktp_images', 'public');
            $pengaduan->image_ktp = $ktpPath;
        }

        $pengaduan->save();

        return redirect()->back()->with('success', 'Pengaduan berhasil dikirim. Nomor tiket Anda: ' . $ticketNumber . ' . Tolong simpan nomor tiket ini untuk mengecek status pengaduan Anda nanti!');
    }

    public function search(Request $request)
    {
        $request->validate(['ticket_number' => 'required|string']);
    
        $pengaduan = Pengaduan::where('ticket_number', $request->ticket_number)->first();
    
        if (!$pengaduan) {
            return response()->json(['success' => false, 'message' => 'Nomor tiket tidak ditemukan.']);
        }
    
        return response()->json([
            'success' => true,
            'ticket_number' => $pengaduan->ticket_number,
            'status' => $pengaduan->status_pengaduan ?? 'Dalam Proses',
            'description' => $pengaduan->deskripsi_pengaduan
        ]);
    }
}
