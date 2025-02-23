<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WiFi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminPetaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $wifi = WiFi::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                         ->orWhere('lokasi', 'like', '%' . $search . '%')
                         ->orWhere('ssid', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('admin.peta', compact('wifi'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'titik' => 'required|string|regex:/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/',
            'ssid' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'status' => 'required|in:Online,Offline',
        ]);

        WiFi::create([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'titik' => $request->titik,
            'ssid' => $request->ssid,
            'password' => $request->password,
            'status' => $request->status,
            'status_validasi' => 'Pending',
            'komentar' => null,
        ]);

        return redirect()->route('admin.peta')->with('success', 'WiFi berhasil ditambahkan, menunggu validasi super admin.');
    }

    public function edit(WiFi $wifi)
    {
        return view('admin.edit', compact('wifi'));
    }

    public function update(Request $request, WiFi $wifi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'titik' => 'required|string|regex:/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/',
            'ssid' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'status' => 'required|in:Online,Offline',
        ]);

        $wifi->update($request->all());

        return redirect()->route('admin.peta')->with('success', 'WiFi berhasil diperbarui.');
    }

    public function destroy(WiFi $wifi)
    {
        $wifi->delete();

        return redirect()->route('admin.peta')->with('success', 'WiFi berhasil dihapus.');
    }

    public function map()
    {
        $wifi = WiFi::where('status_validasi', 'Disetujui')->get();
        return view('admin.map', compact('wifi'));
    }

    public function validateWiFi(Request $request, WiFi $wifi)
    {
        $request->validate([
            'status_validasi' => 'required|in:Disetujui,Ditolak',
            'komentar' => 'nullable|string|max:255',
        ]);

        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.peta')->with('error', 'Anda tidak memiliki izin untuk memvalidasi.');
        }

        $wifi->update([
            'status_validasi' => $request->status_validasi,
            'komentar' => $request->status_validasi === 'Ditolak' ? $request->komentar : null,
        ]);

        return redirect()->route('superadmin.peta')->with('success', 'Status validasi WiFi diperbarui.');
    }

    public function export()
    {
        $wifiData = WiFi::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Lokasi WiFi');
        $sheet->setCellValue('C1', 'Lihat Lokasi');
        $sheet->setCellValue('D1', 'Titik');
        $sheet->setCellValue('E1', 'SSID');
        $sheet->setCellValue('F1', 'Password');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Status Validasi');
        $sheet->setCellValue('I1', 'Komentar');

        $row = 2;
        foreach ($wifiData as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->nama);
            $sheet->setCellValue('C' . $row, $data->lokasi);
            $sheet->setCellValue('D' . $row, $data->titik);
            $sheet->setCellValue('E' . $row, $data->ssid);
            $sheet->setCellValue('F' . $row, $data->password);
            $sheet->setCellValue('G' . $row, $data->status);
            $sheet->setCellValue('H' . $row, $data->status_validasi);
            $sheet->setCellValue('I' . $row, $data->komentar);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'wifi_list_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'wifi');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
