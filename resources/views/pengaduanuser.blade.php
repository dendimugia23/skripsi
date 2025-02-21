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
                            <label for="name" class="form-label">Nama (Opsional)</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda">
                        </div>

                        <!-- Email (Optional) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Opsional, untuk tindak lanjut)</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda">
                        </div>

                        <!-- Kategori Pengaduan -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori Pengaduan</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Koneksi Lemot" {{ old('category') == 'Koneksi Lemot' ? 'selected' : '' }}>Koneksi Lemot</option>
                                <option value="WiFi Mati" {{ old('category') == 'WiFi Mati' ? 'selected' : '' }}>WiFi Mati</option>
                                <option value="Area Tidak Terjangkau" {{ old('category') == 'Area Tidak Terjangkau' ? 'selected' : '' }}>Area Tidak Terjangkau</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Deskripsi Pengaduan -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Pengaduan</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Jelaskan masalah yang Anda alami...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Upload Bukti (Optional) -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Bukti (Opsional)</label>
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