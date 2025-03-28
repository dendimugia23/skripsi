@extends('layouts.superadmin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <a href="{{ route('superadmin.peta') }}" class="text-decoration-none text-dark">
            <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Daftar WiFi</h5>
        </a>

        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.map') }}" class="btn btn-success btn-sm">
                <i class="fas fa-map-marker-alt"></i> Peta
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-3">
            <form action="{{ route('superadmin.peta') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama, lokasi, atau SSID..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        @if($wifi->isEmpty())
            <div class="alert alert-info text-center">Tidak ada data WiFi yang tersedia.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Lokasi WiFi</th>
                            <th>Lihat Lokasi</th>
                            <th>Titik</th>
                            <th>SSID</th>
                            <th>Password</th>
                            <th>Status Validasi</th>
                            <th>Status</th>
                            <th>Total Pengguna</th> <!-- New column for Total Pengguna -->
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wifi as $index => $data)
                        <tr class="text-center">
                            <td>{{ ($wifi->currentPage() - 1) * $wifi->perPage() + $index + 1 }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->lokasi }}</td>
                            <td>{{ $data->titik }}</td>
                            <td>{{ $data->ssid }}</td>
                            <td>{{ $data->password }}</td>
                            <td>
                                <span class="badge bg-{{ $data->status_validasi == 'Pending' ? 'warning' : ($data->status_validasi == 'Disetujui' ? 'success' : 'danger') }}">
                                    {{ $data->status_validasi }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $data->status == 'Online' ? 'success' : 'danger' }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td>{{ $data->total_pengguna ?? 0 }}</td> <!-- Displaying Total Pengguna -->
                            <td>
                                @if($data->status_validasi === 'Pending')
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.25rem 0.5rem;">
                                            Pilih Validasi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 120px;">
                                            <li>
                                                <form action="{{ route('superadmin.validasi', $data->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status_validasi" value="Disetujui">
                                                    <button type="submit" class="dropdown-item text-success fw-bold d-flex align-items-center gap-2">
                                                        <i class="fas fa-check"></i> Disetujui
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $data->id }}">
                                                    <i class="fas fa-times"></i> Ditolak
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal-{{ $data->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel">Tolak Validasi WiFi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('superadmin.validasi', $data->id) }}" method="POST" onsubmit="return validateRejection(this)">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <input type="hidden" name="status_validasi" value="Ditolak">
                                                        <div class="mb-3">
                                                            <label for="komentar" class="form-label">Komentar Penolakan</label>
                                                            <textarea name="komentar" class="form-control" rows="3" placeholder="Berikan alasan penolakan..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Sudah Diverifikasi</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $wifi->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    function validateRejection(form) {
        const komentar = form.komentar.value.trim();
        if (!komentar) {
            alert('Harap berikan alasan penolakan sebelum mengirim.');
            return false;
        }
        return true;
    }
</script>
@endpush
