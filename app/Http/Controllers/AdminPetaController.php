<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WiFi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;


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
            'total_pengguna' => 'required|integer|min:0', // Validate total_pengguna to ensure it is a number greater than or equal to 0

        ]);

        // Store the WiFi data including 'total_pengguna' initialized as 0
        WiFi::create([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'titik' => $request->titik,
            'ssid' => $request->ssid,
            'password' => $request->password,
            'status' => $request->status,
            'status_validasi' => 'Pending',
            'komentar' => null,
            'total_pengguna' => $request->total_pengguna, // Save the total_pengguna

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
            'total_pengguna' => 'required|integer|min:0'
        ]);

        // Update the WiFi data including 'total_pengguna' if necessary
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
        // Fetch only WiFi entries with approved or pending validation status
        $wifi = WiFi::whereIn('status_validasi', ['Disetujui', 'Pending'])->get();

        return view('admin.map', compact('wifi'));
    }

    public function validateWiFi(Request $request, WiFi $wifi)
    {
        $request->validate([
            'status_validasi' => 'required|in:Disetujui,Ditolak',
            'komentar' => 'nullable|string|max:255',
        ]);

        // Check if the current user is a superadmin
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.peta')->with('error', 'Anda tidak memiliki izin untuk memvalidasi.');
        }

        // Update the WiFi validation status and komentar if rejected
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
        $sheet->setCellValue('H1', 'Total Pengguna'); // Added 'Total Pengguna' column

        $row = 2;
        foreach ($wifiData as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->nama);
            $sheet->setCellValue('C' . $row, $data->lokasi);
            $sheet->setCellValue('D' . $row, $data->titik);
            $sheet->setCellValue('E' . $row, $data->ssid);
            $sheet->setCellValue('F' . $row, $data->password);
            $sheet->setCellValue('G' . $row, $data->status);
            $sheet->setCellValue('H' . $row, $data->total_pengguna); // Added total_pengguna
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'wifi_list_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'wifi');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }


public function exportPDF()
{
    $wifiData = WiFi::all(); // Ambil semua data WiFi, sama kayak di export()

    $htmlTable = '
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Lokasi WiFi</th>
                <th>Lihat Lokasi</th>
                <th>Titik</th>
                <th>SSID</th>
                <th>Password</th>
                <th>Status</th>
                <th>Total Pengguna</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($wifiData as $index => $data) {
        $htmlTable .= '
            <tr>
                <td>' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($data->nama) . '</td>
                <td>' . htmlspecialchars($data->lokasi) . '</td>
                <td>' . htmlspecialchars($data->titik) . '</td>
                <td>' . htmlspecialchars($data->ssid) . '</td>
                <td>' . htmlspecialchars($data->password) . '</td>
                <td>' . htmlspecialchars($data->status) . '</td>
                <td>' . htmlspecialchars($data->total_pengguna) . '</td>
            </tr>';
    }

    $htmlTable .= '</tbody></table>';

    $styledHtml = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            h2 { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 5px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <h2>Daftar WiFi Publik</h2>
        ' . $htmlTable . '
    </body>
    </html>';

    $pdf = Pdf::loadHTML($styledHtml)->setPaper('A4', 'landscape');

    return $pdf->download('wifi_list_' . date('Ymd_His') . '.pdf');
}

}
