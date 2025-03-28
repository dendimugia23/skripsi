<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wifi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('lokasi');
            $table->string('titik');
            $table->string('ssid');
            $table->string('password');
            $table->enum('status', ['Online', 'Offline']);
            $table->enum('status_validasi', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->string('komentar')->nullable();
            $table->unsignedInteger('total_pengguna')->default(0); // Add this line to track the number of users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi');
    }
};
