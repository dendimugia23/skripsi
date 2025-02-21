@extends('layouts.superadmin')
<style>
    .content {
            margin-left: 250px;
            padding: 80px 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
 body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background-color: #f4f6f9;
            color: #333;
 }
.card {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
}

</style>

<div class="content">
    
    <div class="dashboard">
        <div class="card">Jumlah WiFi</div>
        <div class="card">Jumlah WiFi Online</div>
        <div class="card">Jumlah WiFi Offline</div>
        <div class="card">Jumlah Pengaduan</div>
        <div class="card">Jumlah Pengaduan Sudah Divalidasi</div>
        <div class="card">Jumlah Pengaduan Belum Divalidasi</div>
    </div>
</div>