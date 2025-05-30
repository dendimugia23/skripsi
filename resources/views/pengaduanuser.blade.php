@extends('layouts.pengaduan')

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
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Custom Alert for Missing Fields -->
                    <div id="missingFieldsAlert" class="alert alert-warning alert-dismissible fade show" role="alert" style="display:none;">
                        <strong>Perhatian!</strong> Semua kolom wajib diisi sebelum mengirim pengaduan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Complaint Form -->
                    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" id="pengaduanForm" novalidate>
                        @csrf

                        <!-- Nama WiFi -->
                        <div class="mb-3">
                            <label for="nama_wifi" class="form-label">Nama WiFi</label>
                            <select class="form-select" id="nama_wifi" name="nama_wifi" required>
                                <option value="">Pilih atau Ketik Nama WiFi</option>
                                @foreach($wifi as $data)
                                    <option value="{{ $data->nama }}">{{ $data->nama }}</option>
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

                        <!-- Upload Bukti -->
                        <div class="mb-3">
                            <label for="image_pengaduan" class="form-label">Upload Bukti</label>
                            <input type="file" class="form-control" id="image_pengaduan" name="image_pengaduan" accept="image/*" required>
                            <small class="text-muted">Format yang diterima: JPEG, PNG, JPG (Maksimal 2MB)</small>
                        </div>

                        <!-- Upload KTP -->
                        <div class="mb-3">
                            <label for="image_ktp" class="form-label">Upload Foto KTP</label>
                            <input type="file" class="form-control" id="image_ktp" name="image_ktp" accept="image/*" required>
                            <small class="text-muted">Format yang diterima: JPEG, PNG, JPG (Maksimal 2MB)</small>
                        </div>

                        <!-- CAPTCHA -->
                        <div class="mb-3">
                            <label class="form-label">Verifikasi CAPTCHA</label>
                            {!! NoCaptcha::display() !!}
                            {!! NoCaptcha::renderJs() !!}
                            @error('g-recaptcha-response')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Kirim Pengaduan</button>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk Select2 dan Validasi -->
<script>
    $(document).ready(function () {
        $('#nama_wifi').select2({
            placeholder: "Pilih atau Ketik Nama WiFi",
            allowClear: true,
            width: '100%'
        });
    });

    document.getElementById('pengaduanForm').addEventListener('submit', function (event) {
        var response = grecaptcha.getResponse();
        var missingFieldsAlert = document.getElementById('missingFieldsAlert');
        var isValid = true;
        var maxFileSize = 2 * 1024 * 1024; // 2MB

        var requiredFields = document.querySelectorAll('select[required], textarea[required], input[type="file"][required]');
        requiredFields.forEach(function (field) {
            if (!field.value) {
                isValid = false;
            }
        });

        // Cek ukuran file Bukti
        var imagePengaduan = document.getElementById('image_pengaduan');
        if (imagePengaduan.files.length > 0 && imagePengaduan.files[0].size > maxFileSize) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Maaf, file Bukti Pengaduan tidak boleh lebih dari 2MB.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Cek ukuran file KTP
        var imageKtp = document.getElementById('image_ktp');
        if (imageKtp.files.length > 0 && imageKtp.files[0].size > maxFileSize) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Maaf, file Foto KTP tidak boleh lebih dari 2MB.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Validasi CAPTCHA dan field lainnya
        if (response.length === 0 || !isValid) {
            event.preventDefault();
            missingFieldsAlert.style.display = 'block';
        } else {
            missingFieldsAlert.style.display = 'none';
        }
    });
</script>
@endsection
