@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
              <div class="card-header bg-light text-black text-center border-0">
                <h2 class="mb-0">Form Pengaduan WiFi Publik</h2>
              </div>
            
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Complaint Form -->
                    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Nama (Optional) -->
                        <div class="mb-3">
                            <label for="nama_wifi" class="form-label">Nama WiFi</label>
                            <select class="form-select" id="nama_wifi" name="nama_wifi" required>
                                <option value="" disabled selected>Pilih WiFi</option>
                                @foreach($wifi as $data)
                                    <option value="{{ $data->nama }}" {{ old('nama_wifi') == $data->nama ? 'selected' : '' }}>
                                        {{ $data->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        
                       
                        
                        <!-- Kategori Pengaduan -->
                        <div class="mb-3">
                            <label for="kategori_pengaduan" class="form-label">Kategori Pengaduan</label>
                            <select class="form-select" id="kategori_pengaduan" name="kategori_pengaduan" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Tidak Ada Koneksi" {{ old('kategori_pengaduan') == 'Tidak Ada Koneksi' ? 'selected' : '' }}>Tidak Ada Koneksi</option>
                                <option value="WiFi Mati" {{ old('kategori_pengaduan') == 'WiFi Mati' ? 'selected' : '' }}>WiFi Mati</option>
                                <option value="Lainnya" {{ old('kategori_pengaduan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        
                        <!-- Deskripsi Pengaduan -->
                        <div class="mb-3">
                            <label for="deskripsi_pengaduan" class="form-label">Deskripsi Pengaduan</label>
                            <textarea class="form-control" id="deskripsi_pengaduan" name="deskripsi_pengaduan" rows="4" required placeholder="Jelaskan masalah yang Anda alami...">{{ old('deskripsi_pengaduan') }}</textarea>
                        </div>
                        <!-- Upload Bukti (Optional) -->
                        <div class="mb-3">
                            <label for="image_pengaduan" class="form-label">Upload Bukti (Opsional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Format yang diterima: JPEG, PNG, JPG (Maksimal 2MB)</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Kirim Pengaduan</button>
                        </div>
                    </form>

                    <!-- Kembali ke Halaman Utama -->
                    <div class="mt-3 text-center">
                        <a href="{{ route('index') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection