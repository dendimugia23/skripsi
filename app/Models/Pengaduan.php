<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'nama_wifi',
        'kategori_pengaduan',
        'deskripsi_pengaduan',
        'image_pengaduan',
        'image_ktp', // <- kolom untuk menyimpan foto KTP
        'status_pengaduan',
    ];
}
