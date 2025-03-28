@extends('layouts.admin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Edit Data WiFi</h5>
        <a href="{{ route('admin.peta') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.update', $wifi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama WiFi</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $wifi->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lihat Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ $wifi->lokasi }}" required>
            </div>
            <div class="mb-3">
                <label for="titik" class="form-label">Titik (Latitude, Longitude)</label>
                <input type="text" class="form-control" id="titik" name="titik" value="{{ $wifi->titik }}" required>
            </div>
            <div class="mb-3">
                <label for="ssid" class="form-label">SSID</label>
                <input type="text" class="form-control" id="ssid" name="ssid" value="{{ $wifi->ssid }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" id="password" name="password" value="{{ $wifi->password }}" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Online" {{ $wifi->status == 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Offline" {{ $wifi->status == 'Offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>
            <!-- Added total_pengguna field -->
            <div class="mb-3">
                <label for="total_pengguna" class="form-label">Total Pengguna</label>
                <input type="number" class="form-control" id="total_pengguna" name="total_pengguna" value="{{ $wifi->total_pengguna }}" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
