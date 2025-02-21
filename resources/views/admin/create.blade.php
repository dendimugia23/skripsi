@extends('layouts.admin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Tambah Data WiFi</h5>
        <a href="{{ route('admin.peta') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label">Nama WiFi</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="lihat_lokasi" class="form-label">Lihat Lokasi (Link Google Maps)</label>
                <input type="url" class="form-control" id="lokasi" name="lokasi" required>
            </div>
            <div class="mb-3">
                <label for="titik" class="form-label">Titik (Latitude, Longitude)</label>
                <input type="text" class="form-control" id="titik" name="titik" required>
                
            </div>
            <div class="mb-3">
                <label for="ssid" class="form-label">SSID</label>
                <input type="text" class="form-control" id="ssid" name="ssid" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection