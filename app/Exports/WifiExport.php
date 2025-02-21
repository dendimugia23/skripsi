<?php

namespace App\Exports;

use App\Models\Wifi;
use Maatwebsite\Excel\Concerns\FromCollection;

class WifiExport implements FromCollection
{
    public function collection()
    {
        return Wifi::all(); // Ambil semua data WiFi
    }
}
