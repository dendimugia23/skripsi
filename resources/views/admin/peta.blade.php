@extends('layouts.admin')

@section('content')
<div class="card shadow-sm" style="background-color: #ffffff; border: none;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #ffffff; border-bottom: none;">
        <a href="{{ route('admin.peta') }}" class="text-decoration-none text-dark">
            <h5 class="card-title mb-0 fw-bold" style="font-size: 1.25rem;">Daftar WiFi</h5>
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah
            </a>
            <a href="{{ route('admin.map') }}" class="btn btn-success btn-sm">
                <i class="fas fa-map-marker-alt me-1"></i> Map
            </a>
            <a href="{{ route('admin.wifi.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('admin.wifi.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-3">
            <form action="{{ route('admin.peta') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama, lokasi, atau SSID..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama WiFi</th>
                        <th>Lokasi WiFi</th>
                        <th>Titik</th>
                        <th>SSID</th>
                        <th>Password</th>
                        <th>Total Pengguna</th>
                        <th>Status Validasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td>{{ $data->total_pengguna ?? 0 }}</td>
                            <td>
                                <span class="badge bg-{{ $data->status_validasi == 'Pending' ? 'warning' : ($data->status_validasi == 'Ditolak' ? 'danger' : 'success') }}">
                                    {{ $data->status_validasi }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $data->status == 'Online' ? 'success' : 'danger' }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.edit', $data->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    
                                    <form action="{{ route('admin.destroy', $data->id) }}" method="POST" class="d-inline" id="delete-form-{{ $data->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $data->id }})">Hapus</button>
                                    </form>

                                    @if($data->status_validasi == 'Ditolak')
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#komentarModal-{{ $data->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>

                                        <!-- Komentar Modal -->
                                        <div class="modal fade" id="komentarModal-{{ $data->id }}" tabindex="-1" aria-labelledby="komentarModalLabel-{{ $data->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="komentarModalLabel-{{ $data->id }}">Alasan Penolakan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $data->komentar ?: 'Tidak ada komentar' }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
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
