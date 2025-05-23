@extends('layouts.superadmin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <a href="{{ route('superadmin.peta') }}" class="text-decoration-none text-dark">
            <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Daftar WiFi</h5>
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.map') }}" class="btn btn-success btn-sm">
                <i class="fas fa-map-marker-alt"></i> Map
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
                        <th>Total Pengguna</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wifi as $index => $data)
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
                            <td>{{ $data->total_pengguna ?? 0 }}</td>
                            <td>
                                @if($data->status_validasi === 'Pending')
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.25rem 0.5rem;">
                                            Pilih Validasi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 120px;">
                                            <li>
                                                <button type="button" class="dropdown-item text-success fw-bold d-flex align-items-center gap-2" onclick="konfirmasiValidasi('{{ $data->id }}')">
                                                    <i class="fas fa-check"></i> Disetujui
                                                </button>
                                                <form id="form-validasi-{{ $data->id }}" action="{{ route('superadmin.validasi', $data->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status_validasi" value="Disetujui">
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $data->id }}">
                                                    <i class="fas fa-times"></i> Ditolak
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Modal Penolakan -->
                                    <div class="modal fade" id="rejectModal-{{ $data->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel-{{ $data->id }}">Tolak Validasi WiFi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('superadmin.validasi', $data->id) }}" method="POST" onsubmit="return validateRejection(this)">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <input type="hidden" name="status_validasi" value="Ditolak">
                                                        <div class="mb-3">
                                                            <label for="komentar" class="form-label">Komentar Penolakan</label>
                                                            <textarea name="komentar" class="form-control" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
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
                                    <span class="badge bg-success">Terverifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Data WiFi tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $wifi->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>


@endsection
