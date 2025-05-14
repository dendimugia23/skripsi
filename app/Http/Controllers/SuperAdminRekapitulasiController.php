<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wifi;
use App\Models\Pengaduan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class SuperAdminRekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', now()->year);

        if (!$bulan) {
            $tahun = now()->year;
        }

        $wifiBaru = $this->getWifiBaru($bulan, $tahun);
        $rekap = $this->getRekapData($bulan, $tahun);

        return view('superadmin.rekapitulasi', compact('rekap', 'bulan', 'tahun', 'wifiBaru'));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', now()->year);

        if (!$bulan) {
            $tahun = now()->year;
        }

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $rekap = $this->getRekapData($bulan, $tahun);
        $wifiBaru = $this->getWifiBaru($bulan, $tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $judul = 'Rekapitulasi Data WiFi - ' . ($bulan ? $namaBulan[$bulan] : 'Semua Bulan') . ' ' . $tahun;
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', $judul);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama / Lokasi WiFi');
        $sheet->setCellValue('C3', 'Status WiFi');
        $sheet->setCellValue('D3', 'Jumlah Pengaduan');
        $sheet->setCellValue('E3', 'Kategori Pengaduan');
        $sheet->setCellValue('F3', 'Total Pengguna');
        $sheet->setCellValue('G3', 'Keterangan');
        $sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal('center');

        $row = 4;
        $totalWifiBaru = 0;

        foreach ($rekap as $index => $data) {
            $sheet->setCellValue("A{$row}", $data['no']);
            $sheet->setCellValue("B{$row}", $data['lokasi']);
            $sheet->setCellValue("C{$row}", $data['status']);
            $sheet->setCellValue("D{$row}", $data['jumlah_pengaduan']);
            $sheet->setCellValue("E{$row}", $data['kategori_pengaduan']);
            $sheet->setCellValue("F{$row}", $data['total_pengguna']);

            $isBaru = $wifiBaru->contains('id', $data['id_wifi']);
            if ($isBaru) {
                $sheet->setCellValue("G{$row}", 'WiFi Baru');
                $totalWifiBaru++;
            } else {
                $sheet->setCellValue("G{$row}", '-');
            }

            $row++;
        }

        $sheet->mergeCells("A{$row}:G{$row}");
        $sheet->setCellValue("A{$row}", 'Total Titik WiFi Baru: ' . $totalWifiBaru);
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Rekapitulasi_' . ($bulan ? $namaBulan[$bulan] . '_' : '') . $tahun . '.xlsx';
        $tempPath = storage_path('app/public/' . $filename);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function exportPDF(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', now()->year);

        if (!$bulan) {
            $tahun = now()->year;
        }

        $rekap = $this->getRekapData($bulan, $tahun);
        $wifiBaru = $this->getWifiBaru($bulan, $tahun);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $judul = 'Rekapitulasi Data WiFi - ' . ($bulan ? $namaBulan[$bulan] : 'Semua Bulan') . ' ' . $tahun;

        $html = View::make('superadmin.rekapitulasi', compact('rekap', 'bulan', 'tahun', 'wifiBaru'))->render();
        preg_match('/<table.*?<\/table>/s', $html, $matches);
        $tableHtml = $matches[0] ?? '<p>Data tidak tersedia</p>';

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
            <h2>' . $judul . '</h2>
            ' . $tableHtml . '
        </body>
        </html>';

        $pdf = Pdf::loadHTML($styledHtml)->setPaper('A4', 'landscape');

        return $pdf->download('Rekapitulasi_' . ($bulan ?? 'Semua') . '_' . $tahun . '.pdf');
    }

    private function getRekapData($bulan, $tahun)
    {
        $tahun = $tahun ?: now()->year;

        $dataWifi = Wifi::where('status_validasi', 'Disetujui')
            ->whereYear('created_at', '<=', $tahun)
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereMonth('created_at', '<=', $bulan);
            })
            ->get();

        return $dataWifi->map(function ($wifi, $index) use ($bulan, $tahun) {
            $query = Pengaduan::where('nama_wifi', $wifi->nama)
                ->where('status_pengaduan', '!=', 'Ditolak');

            if ($bulan) $query->whereMonth('created_at', $bulan);
            if ($tahun) $query->whereYear('created_at', $tahun);

            $pengaduan = $query->get();
            $jumlahPengaduan = $pengaduan->count();

            $kategoriPengaduan = $pengaduan->groupBy('kategori_pengaduan')->map(function ($item, $kategori) {
                return $kategori . ' (' . count($item) . ')';
            })->implode(', ');

            return [
                'no' => $index + 1,
                'id_wifi' => $wifi->id,
                'lokasi' => $wifi->nama,
                'status' => $wifi->status,
                'jumlah_pengaduan' => $jumlahPengaduan,
                'kategori_pengaduan' => $kategoriPengaduan ?: '-',
                'total_pengguna' => $wifi->total_pengguna,
            ];
        });
    }

    private function getWifiBaru($bulan, $tahun)
    {
        $tahun = $tahun ?: now()->year;

        return Wifi::where('status_validasi', 'Disetujui')
            ->whereYear('created_at', $tahun)
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->get();
    }
}
