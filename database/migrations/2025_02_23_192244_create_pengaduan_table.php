<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('nama_wifi')->nullable();
            $table->string('kategori_pengaduan')->nullable();
            $table->text('deskripsi_pengaduan')->nullable();
            $table->string('image_pengaduan')->nullable();
            $table->string('image_ktp')->nullable();
            $table->enum('status_pengaduan', ['Proses', 'Tervalidasi', 'Ditolak'])->default('Proses'); // ✅ Tambah 'Ditolak'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
