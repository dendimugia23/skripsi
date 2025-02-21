<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WiFi extends Model
{
    use HasFactory;

    protected $table = 'wifi'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'nama',
        'lokasi',
        'titik',
        'ssid',
        'password',
        'status',
        'status_validasi', // Tambahkan ini
    ];
}
